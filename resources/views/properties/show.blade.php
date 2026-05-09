<x-app-layout title="{{ $property->title }} | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <x-app-nav />
        </header>

        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow">{{ $property->listing_status_label }}</div>
                    <h1 class="tt-title tt-title-medium">{{ $property->title }}</h1>
                    <p class="tt-lead">{{ $property->address }}, {{ $property->city }}{{ $property->province ? ', '.$property->province : '' }}</p>
                </div>

                @if (session('status'))
                    <div class="tt-alert tt-alert-success">{{ session('status') }}</div>
                @endif

                <div class="tt-columns">
                    <section class="tt-panel">
                        <div class="tt-label">Listing Details</div>
                        <div class="tt-info-list">
                            <div class="tt-info-item"><dt>Type</dt><dd>{{ $property->property_type }}</dd></div>
                            <div class="tt-info-item"><dt>Price</dt><dd>PHP {{ number_format((float) $property->price, 2) }}</dd></div>
                            <div class="tt-info-item"><dt>Bedrooms</dt><dd>{{ $property->bedrooms ?: 'N/A' }}</dd></div>
                            <div class="tt-info-item"><dt>Bathrooms</dt><dd>{{ $property->bathrooms ?: 'N/A' }}</dd></div>
                            <div class="tt-info-item"><dt>Agent</dt><dd>{{ $property->agent?->name ?? 'N/A' }}</dd></div>
                            <div class="tt-info-item"><dt>Description</dt><dd>{{ $property->description ?: 'No description provided.' }}</dd></div>
                            <div class="tt-info-item"><dt>Amenities</dt><dd>{{ $property->amenities->pluck('name')->join(', ') ?: 'No amenities listed.' }}</dd></div>
                        </div>
                    </section>

                    <aside class="tt-stack">
                        @if (auth()->user()->isAdmin())
                            <section class="tt-panel">
                                <div class="tt-label">Listing Approval</div>
                                <form method="POST" action="{{ route('properties.approve', $property) }}" class="tt-form" style="margin-top: 20px;">
                                    @csrf
                                    @method('PATCH')
                                    <div class="tt-field">
                                        <label for="listing_status">Update status</label>
                                        <select id="listing_status" name="listing_status" class="tt-select">
                                            @foreach (['approved', 'rejected', 'available', 'under_offer', 'inactive', 'sold'] as $status)
                                                <option value="{{ $status }}" @selected($property->listing_status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Save status</button>
                                </form>
                            </section>
                        @endif

                        @if (auth()->user()->isClient())
                            <section class="tt-panel">
                                <div class="tt-label">Submit Inquiry</div>
                                <form method="POST" action="{{ route('inquiries.store', $property) }}" class="tt-form" style="margin-top: 20px;">
                                    @csrf
                                    <div class="tt-field">
                                        <label for="subject">Subject</label>
                                        <input id="subject" name="subject" class="tt-input" placeholder="Ask about this property">
                                    </div>
                                    <div class="tt-field">
                                        <label for="message">Message</label>
                                        <textarea id="message" name="message" class="tt-textarea" required></textarea>
                                    </div>
                                    <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Send inquiry</button>
                                </form>
                            </section>

                            <section class="tt-panel">
                                <div class="tt-label">Schedule Viewing</div>
                                <form method="POST" action="{{ route('viewings.store', $property) }}" class="tt-form" style="margin-top: 20px;">
                                    @csrf
                                    <div class="tt-field">
                                        <label for="scheduled_at">Preferred schedule</label>
                                        <input id="scheduled_at" name="scheduled_at" type="datetime-local" class="tt-input" required>
                                    </div>
                                    <div class="tt-field">
                                        <label for="notes">Notes</label>
                                        <textarea id="notes" name="notes" class="tt-textarea"></textarea>
                                    </div>
                                    <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Request viewing</button>
                                </form>
                            </section>

                            <section class="tt-panel">
                                <div class="tt-label">Make Offer</div>

                                @if (in_array($property->listing_status, ['under_offer', 'sold'], true))
                                    <div class="tt-alert tt-alert-info">
                                        This listing already has an accepted offer and is no longer accepting new offer submissions.
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('offers.store', $property) }}" class="tt-form" style="margin-top: 20px;">
                                        @csrf
                                        <div class="tt-field">
                                            <label for="offer_amount">Offer amount</label>
                                            <input id="offer_amount" name="offer_amount" type="number" step="0.01" class="tt-input" required>
                                        </div>
                                        <div class="tt-field">
                                            <label for="offer_message">Message</label>
                                            <textarea id="offer_message" name="message" class="tt-textarea"></textarea>
                                        </div>
                                        <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Submit offer</button>
                                    </form>
                                @endif
                            </section>
                        @endif
                    </aside>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
