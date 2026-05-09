<x-app-layout title="Listings | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <x-app-nav />
        </header>

        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow">Property Search and Browse</div>
                    <h1 class="tt-title tt-title-medium">Property listings</h1>
                    <p class="tt-lead">Search, browse, review, and manage listings based on your role.</p>
                </div>

                @if (session('status'))
                    <div class="tt-alert tt-alert-success">{{ session('status') }}</div>
                @endif

                <section class="tt-panel">
                    <form method="GET" class="tt-toolbar">
                        <div class="tt-field">
                            <label for="search">Search</label>
                            <input id="search" name="search" value="{{ request('search') }}" class="tt-input" placeholder="Title, city, address">
                        </div>
                        <div class="tt-field">
                            <label for="city">City</label>
                            <input id="city" name="city" value="{{ request('city') }}" class="tt-input" placeholder="City">
                        </div>
                        <div class="tt-field">
                            <label for="property_type">Type</label>
                            <select id="property_type" name="property_type" class="tt-select">
                                <option value="">All types</option>
                                @foreach ($propertyTypes as $type)
                                    <option value="{{ $type }}" @selected(request('property_type') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="tt-field">
                            <label for="listing_status">Status</label>
                            <select id="listing_status" name="listing_status" class="tt-select">
                                <option value="">All statuses</option>
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected(request('listing_status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="tt-field">
                            <label for="min_price">Min price</label>
                            <input id="min_price" name="min_price" type="number" value="{{ request('min_price') }}" class="tt-input">
                        </div>
                        <div class="tt-field">
                            <label for="max_price">Max price</label>
                            <input id="max_price" name="max_price" type="number" value="{{ request('max_price') }}" class="tt-input">
                        </div>
                        <div class="tt-field">
                            <label for="bedrooms">Bedrooms</label>
                            <input id="bedrooms" name="bedrooms" type="number" value="{{ request('bedrooms') }}" class="tt-input">
                        </div>
                        <div>
                            <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Search listings</button>
                        </div>
                    </form>
                </section>

                <section class="tt-panel">
                    <div class="tt-table-wrap">
                        <table class="tt-table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Agent</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($properties as $property)
                                    <tr>
                                        <td>
                                            <strong>{{ $property->title }}</strong>
                                            {{ $property->city }}<br>
                                            {{ $property->property_type }}
                                        </td>
                                        <td>{{ $property->agent?->name ?? 'N/A' }}</td>
                                        <td><span class="tt-badge">{{ $property->listing_status_label }}</span></td>
                                        <td>PHP {{ number_format((float) $property->price, 2) }}</td>
                                        <td class="tt-actions-row">
                                            <a href="{{ route('properties.show', $property) }}" class="tt-btn">View</a>
                                            @if (auth()->user()->isAdmin() || (auth()->user()->isAgent() && $property->agent_id === auth()->id()))
                                                <a href="{{ route('properties.edit', $property) }}" class="tt-btn">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"><p class="tt-empty">No listings matched your search yet.</p></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="tt-pager">{{ $properties->links() }}</div>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
