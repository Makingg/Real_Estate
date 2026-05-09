<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithRoles;
use App\Models\Property;
use App\Models\Viewing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ViewingController extends Controller
{
    use InteractsWithRoles;

    public function index(Request $request): View
    {
        $user = $request->user();

        $viewings = Viewing::with(['property', 'client', 'agent'])
            ->when($user->isAgent(), fn ($query) => $query->where('agent_id', $user->id))
            ->when($user->isClient(), fn ($query) => $query->where('client_id', $user->id))
            ->latest('scheduled_at')
            ->paginate(12);

        return view('viewings.index', compact('viewings'));
    }

    public function store(Request $request, Property $property): RedirectResponse
    {
        $this->requireRole($request, ['client']);

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
            'notes' => ['nullable', 'string'],
        ]);

        Viewing::create([
            ...$validated,
            'property_id' => $property->id,
            'client_id' => $request->user()->id,
            'agent_id' => $property->agent_id,
        ]);

        return back()->with('status', 'Viewing request scheduled successfully.');
    }

    public function update(Request $request, Viewing $viewing): RedirectResponse
    {
        $this->requireRole($request, ['admin', 'agent']);
        abort_unless($request->user()->isAdmin() || $viewing->agent_id === $request->user()->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $viewing->update($validated);

        return back()->with('status', 'Viewing updated.');
    }
}
