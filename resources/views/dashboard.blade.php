<x-app-layout title="Dashboard | TirahanTech">
    @php($user = auth()->user())

    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <div class="tt-actions">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="tt-btn">Log out</button>
                </form>
            </div>
        </header>

        <main class="tt-main">
            <div class="tt-main-grid tt-main-grid--dashboard">
                <section class="tt-copy">
                    <div class="tt-eyebrow tt-eyebrow-cyan">{{ strtoupper($user->role) }} Dashboard</div>
                    <div>
                        <h1 class="tt-title tt-title-medium">Welcome, {{ $user->name }}.</h1>
                        <p class="tt-lead">
                        </p>
                    </div>

                    <x-app-nav />

                    <section class="tt-panel">
                        <div class="tt-label">Quick Overview</div>
                        <div class="tt-stat-grid" style="margin-top: 20px;">
                            @foreach ($stats as $label => $value)
                                <article class="tt-stat-card">
                                    <p class="tt-stat-label">{{ $label }}</p>
                                    <p class="tt-stat-value">{{ $value }}</p>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <section class="tt-panel">
                        <div class="tt-label">Recent Listings</div>
                        <div class="tt-table-wrap" style="margin-top: 20px;">
                            <table class="tt-table">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentProperties as $property)
                                        <tr>
                                            <td>
                                                <strong>{{ $property->title }}</strong>
                                                {{ $property->city }}
                                            </td>
                                            <td>{{ $property->agent?->name ?? 'Unassigned' }}</td>
                                            <td><span class="tt-badge">{{ str_replace('_', ' ', $property->listing_status) }}</span></td>
                                            <td>PHP {{ number_format((float) $property->price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"><p class="tt-empty">No properties to show yet.</p></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </section>

                <aside class="tt-stack">
                    <section class="tt-panel">
                        <div class="tt-label">Recent Transactions</div>
                        <div class="tt-stack">
                            @forelse ($recentTransactions as $tracking)
                                <article class="tt-card">
                                    <h3 class="tt-card-title">{{ $tracking->property?->title ?? 'Transaction' }}</h3>
                                    <p class="tt-card-copy">
                                        Buyer: {{ $tracking->buyer?->name ?? 'N/A' }}<br>
                                        Agent: {{ $tracking->agent?->name ?? 'N/A' }}<br>
                                        Status: {{ ucfirst($tracking->status) }}<br>
                                        Final price: PHP {{ number_format((float) $tracking->final_price, 2) }}
                                    </p>
                                </article>
                            @empty
                                <p class="tt-empty">No finalized transaction records yet.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="tt-panel">
                        <div class="tt-label">Next Actions</div>
                        <div class="tt-stack">
                            <a href="{{ route('properties.index') }}" class="tt-btn">Open Listings</a>
                            <a href="{{ route('inquiries.index') }}" class="tt-btn">Review Inquiries</a>
                            <a href="{{ route('offers.index') }}" class="tt-btn">Manage Offers</a>
                        </div>
                    </section>
                </aside>
            </div>
        </main>
    </div>
</x-app-layout>
