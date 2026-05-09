<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithRoles;
use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    use InteractsWithRoles;

    public function index(Request $request): View
    {
        $user = $request->user();

        $inquiries = Inquiry::with(['property', 'client', 'agent'])
            ->when($user->isAgent(), fn ($query) => $query->where('agent_id', $user->id))
            ->when($user->isClient(), fn ($query) => $query->where('client_id', $user->id))
            ->latest()
            ->paginate(12);

        return view('inquiries.index', compact('inquiries'));
    }

    public function store(Request $request, Property $property): RedirectResponse
    {
        $this->requireRole($request, ['client']);
        abort_unless(in_array($property->listing_status, ['approved', 'available', 'under_offer'], true), 403);

        $validated = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        Inquiry::create([
            ...$validated,
            'property_id' => $property->id,
            'client_id' => $request->user()->id,
            'agent_id' => $property->agent_id,
        ]);

        return back()->with('status', 'Inquiry submitted successfully.');
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $this->requireRole($request, ['admin', 'agent']);
        abort_unless($request->user()->isAdmin() || $inquiry->agent_id === $request->user()->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:new,responded,closed'],
        ]);

        $inquiry->update([
            'status' => $validated['status'],
            'responded_at' => $validated['status'] === 'responded' ? now() : $inquiry->responded_at,
        ]);

        return back()->with('status', 'Inquiry updated.');
    }
}
