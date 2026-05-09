<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithRoles;
use App\Models\Offer;
use App\Models\Property;
use App\Models\Tracking;
use App\Notifications\OfferRejectedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class OfferController extends Controller
{
    use InteractsWithRoles;

    public function index(Request $request): View
    {
        $user = $request->user();

        $offers = Offer::with(['property', 'client', 'agent', 'tracking'])
            ->when($user->isAgent(), fn ($query) => $query->where('agent_id', $user->id))
            ->when($user->isClient(), fn ($query) => $query->where('client_id', $user->id))
            ->latest()
            ->paginate(12);

        $trackings = Tracking::with(['property', 'buyer', 'agent', 'offer'])
            ->when($user->isAgent(), fn ($query) => $query->where('agent_id', $user->id))
            ->when($user->isClient(), fn ($query) => $query->where('buyer_id', $user->id))
            ->latest()
            ->take(8)
            ->get();

        return view('offers.index', compact('offers', 'trackings'));
    }

    public function store(Request $request, Property $property): RedirectResponse
    {
        $this->requireRole($request, ['client']);

        if (in_array($property->listing_status, ['under_offer', 'sold'], true)) {
            return back()->withErrors(['offer_amount' => 'This listing already has an accepted offer and cannot accept new submissions.']);
        }

        $validated = $request->validate([
            'offer_amount' => ['required', 'numeric', 'min:1'],
            'message' => ['nullable', 'string'],
        ]);

        Offer::create([
            ...$validated,
            'property_id' => $property->id,
            'client_id' => $request->user()->id,
            'agent_id' => $property->agent_id,
            'submitted_at' => now(),
        ]);

        return back()->with('status', 'Offer submitted successfully.');
    }

    public function update(Request $request, Offer $offer): RedirectResponse
    {
        $this->requireRole($request, ['admin', 'agent', 'client']);

        $user = $request->user();

        if ($user->isClient()) {
            abort_unless($offer->client_id === $user->id, 403);
            $status = $request->validate([
                'status' => ['required', 'in:withdrawn'],
            ])['status'];

            $offer->update([
                'status' => $status,
                'reviewed_at' => now(),
            ]);

            return back()->with('status', 'Offer withdrawn.');
        }

        abort_unless($user->isAdmin() || $offer->agent_id === $user->id, 403);

        $status = $request->validate([
            'status' => ['required', 'in:under_review,accepted,rejected'],
        ])['status'];

        $offer->update([
            'status' => $status,
            'reviewed_at' => now(),
        ]);

        if ($status === 'accepted') {
            $property = $offer->property;

            $property->update([
                'listing_status' => 'under_offer',
            ]);

            Tracking::updateOrCreate(
                ['offer_id' => $offer->id],
                [
                    'property_id' => $offer->property_id,
                    'buyer_id' => $offer->client_id,
                    'agent_id' => $offer->agent_id,
                    'processed_by' => $user->id,
                    'final_price' => $offer->offer_amount,
                    'status' => 'processing',
                ]
            );

            $otherOffers = Offer::where('property_id', $property->id)
                ->where('id', '!=', $offer->id)
                ->whereNotIn('status', ['rejected', 'withdrawn'])
                ->get();

            $otherOffers->each(function (Offer $otherOffer) {
                $otherOffer->update([
                    'status' => 'rejected',
                    'reviewed_at' => now(),
                ]);
            });

            $clients = $otherOffers->map->client->unique('id')->filter();
            Notification::send($clients, new OfferRejectedNotification($property, $offer));
        }

        return back()->with('status', 'Offer updated.');
    }

    public function updateTracking(Request $request, Tracking $tracking): RedirectResponse
    {
        $this->requireRole($request, ['admin', 'agent']);
        abort_unless($request->user()->isAdmin() || $tracking->agent_id === $request->user()->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:processing,completed,cancelled'],
        ]);

        $tracking->update([
            'status' => $validated['status'],
            'processed_by' => $request->user()->id,
            'finalized_at' => $validated['status'] === 'completed' ? now() : $tracking->finalized_at,
        ]);

        if ($validated['status'] === 'completed') {
            $tracking->property()->update(['listing_status' => 'sold']);
        }

        return back()->with('status', 'Transaction tracking updated.');
    }
}
