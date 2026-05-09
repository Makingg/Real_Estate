<x-app-layout title="Browse Properties | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('welcome') }}" class="tt-brand">TirahanTech</a>
            <div class="tt-actions">
                <a href="{{ route('welcome') }}" class="tt-btn">Welcome</a>
                <a href="{{ route('login') }}" class="tt-btn">Log in</a>
                <a href="{{ route('register') }}" class="tt-btn tt-btn-primary">Register</a>
            </div>
        </header>

        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow">Guest Browse Access</div>
                    <h1 class="tt-title tt-title-medium">Explore public property listings.</h1>
                    <p class="tt-lead">Guests can search approved listings before creating an account to send inquiries, request viewings, or make offers.</p>
                </div>

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
                            <label for="min_price">Min price</label>
                            <input id="min_price" name="min_price" type="number" value="{{ request('min_price') }}" class="tt-input">
                        </div>
                        <div class="tt-field">
                            <label for="max_price">Max price</label>
                            <input id="max_price" name="max_price" type="number" value="{{ request('max_price') }}" class="tt-input">
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
                                    <th></th>
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
                                        <td><a href="{{ route('browse.show', $property) }}" class="tt-btn">View</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"><p class="tt-empty">No public listings matched your search.</p></td>
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
