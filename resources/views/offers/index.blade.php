<x-app-layout title="Offers | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <x-app-nav />
        </header>
        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow tt-eyebrow-cyan">Transaction and Offer Management</div>
                    <h1 class="tt-title tt-title-medium">Offers and transactions</h1>
                    <p class="tt-lead">Review offer pipelines, accept or reject proposals, and track finalized deals.</p>
                </div>
                @if (session('status'))
                    <div class="tt-alert tt-alert-success">{{ session('status') }}</div>
                @endif

                <div class="tt-columns">
                    <section class="tt-panel">
                        <div class="tt-label">Offers</div>
                        <div class="tt-table-wrap" style="margin-top: 20px;">
                            <table class="tt-table">
                                <thead>
                                    <tr>
                                        <th>Offer</th>
                                        <th>Property</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($offers as $offer)
                                        <tr>
                                            <td>
                                                <strong>PHP {{ number_format((float) $offer->offer_amount, 2) }}</strong>
                                                Buyer: {{ $offer->client?->name ?? 'N/A' }}
                                            </td>
                                            <td>{{ $offer->property?->title ?? 'N/A' }}</td>
                                            <td>
                                                <span class="tt-badge">{{ ucfirst(str_replace('_', ' ', $offer->status)) }}</span>
                                                <form method="POST" action="{{ route('offers.update', $offer) }}" class="tt-inline-form" style="margin-top: 10px;">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if (auth()->user()->isClient())
                                                        @if ($offer->client_id === auth()->id() && ! in_array($offer->status, ['accepted', 'rejected', 'withdrawn'], true))
                                                            <input type="hidden" name="status" value="withdrawn">
                                                            <button type="submit" class="tt-btn">Withdraw</button>
                                                        @endif
                                                    @else
                                                        <select name="status" class="tt-select-sm">
                                                            @foreach (['under_review', 'accepted', 'rejected'] as $status)
                                                                <option value="{{ $status }}" @selected($offer->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="tt-btn tt-btn-primary">Update</button>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"><p class="tt-empty">No offers available yet.</p></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="tt-pager">{{ $offers->links() }}</div>
                    </section>

                    <section class="tt-panel">
                        <div class="tt-label">Tracking</div>
                        <div class="tt-stack">
                            @forelse ($trackings as $tracking)
                                <article class="tt-card">
                                    <h3 class="tt-card-title">{{ $tracking->property?->title ?? 'Transaction record' }}</h3>
                                    <p class="tt-card-copy">
                                        Buyer: {{ $tracking->buyer?->name ?? 'N/A' }}<br>
                                        Status: {{ ucfirst($tracking->status) }}<br>
                                        Final price: PHP {{ number_format((float) $tracking->final_price, 2) }}
                                    </p>
                                    @if (auth()->user()->isAdmin() || auth()->id() === $tracking->agent_id)
                                        <form method="POST" action="{{ route('trackings.update', $tracking) }}" class="tt-inline-form" style="margin-top: 12px;">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="tt-select-sm">
                                                @foreach (['processing', 'completed', 'cancelled'] as $status)
                                                    <option value="{{ $status }}" @selected($tracking->status === $status)>{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="tt-btn tt-btn-primary">Save</button>
                                        </form>
                                    @endif
                                </article>
                            @empty
                                <p class="tt-empty">Accepted offers will create tracking records here.</p>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
