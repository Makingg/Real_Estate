<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Offer;
use App\Models\Property;
use App\Models\Tracking;
use App\Models\User;
use App\Models\Viewing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $stats = match ($user->role) {
            'admin' => [
                'Users' => User::count(),
                'Pending Listings' => Property::where('listing_status', 'pending')->count(),
                'Open Inquiries' => Inquiry::where('status', 'new')->count(),
                'Offers' => Offer::count(),
            ],
            'agent' => [
                'My Listings' => Property::where('agent_id', $user->id)->count(),
                'New Inquiries' => Inquiry::where('agent_id', $user->id)->where('status', 'new')->count(),
                'Scheduled Viewings' => Viewing::where('agent_id', $user->id)->count(),
                'Offers Received' => Offer::where('agent_id', $user->id)->count(),
            ],
            default => [
                'Saved Journey' => Property::whereIn('listing_status', ['approved', 'available', 'under_offer'])->count(),
                'My Inquiries' => Inquiry::where('client_id', $user->id)->count(),
                'My Viewings' => Viewing::where('client_id', $user->id)->count(),
                'My Offers' => Offer::where('client_id', $user->id)->count(),
            ],
        };

        $recentProperties = Property::with('agent')
            ->when(! $user->isAdmin(), function ($query) use ($user) {
                if ($user->isAgent()) {
                    $query->where('agent_id', $user->id);
                } else {
                    $query->whereIn('listing_status', ['approved', 'available', 'under_offer', 'sold']);
                }
            })
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = Tracking::with(['property', 'buyer', 'agent'])
            ->latest()
            ->when($user->isAgent(), fn ($query) => $query->where('agent_id', $user->id))
            ->when($user->isClient(), fn ($query) => $query->where('buyer_id', $user->id))
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentProperties', 'recentTransactions'));
    }
}
