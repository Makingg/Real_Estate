<x-app-layout title="{{ $property->title }} | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('welcome') }}" class="tt-brand">TirahanTech</a>
            <div class="tt-actions">
                <a href="{{ route('browse.index') }}" class="tt-btn">Back to browse</a>
                <a href="{{ route('login') }}" class="tt-btn">Log in</a>
                <a href="{{ route('register') }}" class="tt-btn tt-btn-primary">Register</a>
            </div>
        </header>

        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow">{{ $property->listing_status_label }}</div>
                    <h1 class="tt-title tt-title-medium">{{ $property->title }}</h1>
                    <p class="tt-lead">{{ $property->address }}, {{ $property->city }}{{ $property->province ? ', '.$property->province : '' }}</p>
                </div>

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

                    <aside class="tt-panel">
                        <div class="tt-label">Next Step</div>
                        <p class="tt-lead" style="font-size: 16px; line-height: 1.8; max-width: none;">
                            Create an account or log in to send an inquiry, schedule a viewing, or make an offer on this property.
                        </p>
                        <div class="tt-stack">
                            <a href="{{ route('register') }}" class="tt-btn tt-btn-primary">Register to continue</a>
                            <a href="{{ route('login') }}" class="tt-btn">Already have an account?</a>
                        </div>
                    </aside>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
