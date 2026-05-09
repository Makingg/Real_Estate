<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithRoles;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    use InteractsWithRoles;

    public function index(Request $request): View
    {
        $this->requireRole($request, ['admin']);

        $users = User::query()
            ->when($request->string('role')->toString(), fn ($query, $role) => $query->where('role', $role))
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->requireRole($request, ['admin']);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:agent,client'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password'],
            'is_active' => true,
        ]);

        return back()->with('status', 'User account created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->requireRole($request, ['admin']);

        $validated = $request->validate([
            'role' => ['required', 'in:admin,agent,client'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update($validated);

        return back()->with('status', 'User account updated successfully.');
    }
}
