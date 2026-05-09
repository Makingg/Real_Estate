<x-app-layout title="User Management | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <x-app-nav />
        </header>

        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow tt-eyebrow-cyan">Admin Module</div>
                    <h1 class="tt-title tt-title-medium">User management</h1>
                    <p class="tt-lead">Review all registered accounts, adjust roles, and activate or suspend access.</p>
                </div>

                @if (session('status'))
                    <div class="tt-alert tt-alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="tt-alert tt-alert-error">{{ $errors->first() }}</div>
                @endif

                <section class="tt-panel">
                    <div class="tt-label">Create account</div>
                    <h2 class="tt-section-title">Create a client or agent account</h2>
                    <p class="tt-sublead">Admins can manually add an agent or client account here.</p>

                    <form method="POST" action="{{ route('users.store') }}" class="tt-form-grid">
                        @csrf

                        <div class="tt-field tt-span-2">
                            <label for="name">Full name</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="tt-input" placeholder="Juan Dela Cruz">
                        </div>

                        <div class="tt-field tt-span-2">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="tt-input" placeholder="you@example.com">
                        </div>

                        <div class="tt-field">
                            <label for="role">Role</label>
                            <select id="role" name="role" required class="tt-select">
                                <option value="client" @selected(old('role') === 'client')>Client</option>
                                <option value="agent" @selected(old('role') === 'agent')>Agent</option>
                            </select>
                        </div>

                        <div class="tt-field">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" required class="tt-input" placeholder="At least 8 characters">
                        </div>

                        <div class="tt-field">
                            <label for="password_confirmation">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="tt-input" placeholder="Repeat your password">
                        </div>

                        <div class="tt-span-2">
                            <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Create account</button>
                        </div>
                    </form>
                </section>

                <section class="tt-panel">
                    <form method="GET" class="tt-toolbar">
                        <div class="tt-field">
                            <label for="search">Search</label>
                            <input id="search" name="search" value="{{ request('search') }}" class="tt-input" placeholder="Name or email">
                        </div>
                        <div class="tt-field">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="tt-select">
                                <option value="">All roles</option>
                                @foreach (['admin', 'agent', 'client'] as $role)
                                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Filter users</button>
                        </div>
                    </form>
                </section>

                <section class="tt-panel">
                    <div class="tt-table-wrap">
                        <table class="tt-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $managedUser)
                                    <tr>
                                        <td>
                                            <strong>{{ $managedUser->name }}</strong>
                                            {{ $managedUser->email }}
                                        </td>
                                        <td>{{ ucfirst($managedUser->role) }}</td>
                                        <td>
                                            <span class="tt-badge">{{ $managedUser->is_active ? 'Active' : 'Inactive' }}</span>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('users.update', $managedUser) }}" class="tt-inline-form">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="tt-select-sm">
                                                    @foreach (['admin', 'agent', 'client'] as $role)
                                                        <option value="{{ $role }}" @selected($managedUser->role === $role)>{{ ucfirst($role) }}</option>
                                                    @endforeach
                                                </select>
                                                <select name="is_active" class="tt-select-sm">
                                                    <option value="1" @selected($managedUser->is_active)>Active</option>
                                                    <option value="0" @selected(! $managedUser->is_active)>Inactive</option>
                                                </select>
                                                <button type="submit" class="tt-btn tt-btn-primary">Save</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"><p class="tt-empty">No users matched your filter.</p></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="tt-pager">{{ $users->links() }}</div>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
