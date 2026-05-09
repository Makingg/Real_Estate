@php($editing = $property->exists)

<div class="tt-form-grid">
    <div class="tt-field tt-span-2">
        <label for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $property->title) }}" class="tt-input" required>
    </div>

    <div class="tt-field tt-span-2">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="tt-textarea">{{ old('description', $property->description) }}</textarea>
    </div>

    <div class="tt-field">
        <label for="property_type">Property type</label>
        <input id="property_type" name="property_type" value="{{ old('property_type', $property->property_type) }}" class="tt-input" required>
    </div>

    <div class="tt-field">
        <label for="price">Price</label>
        <input id="price" name="price" type="number" step="0.01" value="{{ old('price', $property->price) }}" class="tt-input" required>
    </div>

    <div class="tt-field tt-span-2">
        <label for="address">Address</label>
        <input id="address" name="address" value="{{ old('address', $property->address) }}" class="tt-input" required>
    </div>

    <div class="tt-field">
        <label for="city">City</label>
        <input id="city" name="city" value="{{ old('city', $property->city) }}" class="tt-input" required>
    </div>

    <div class="tt-field">
        <label for="province">Province</label>
        <input id="province" name="province" value="{{ old('province', $property->province) }}" class="tt-input">
    </div>

    <div class="tt-field">
        <label for="postal_code">Postal code</label>
        <input id="postal_code" name="postal_code" value="{{ old('postal_code', $property->postal_code) }}" class="tt-input">
    </div>

    <div class="tt-field">
        <label for="bedrooms">Bedrooms</label>
        <input id="bedrooms" name="bedrooms" type="number" value="{{ old('bedrooms', $property->bedrooms) }}" class="tt-input">
    </div>

    <div class="tt-field">
        <label for="bathrooms">Bathrooms</label>
        <input id="bathrooms" name="bathrooms" type="number" step="0.1" value="{{ old('bathrooms', $property->bathrooms) }}" class="tt-input">
    </div>

    <div class="tt-field">
        <label for="floor_area">Floor area</label>
        <input id="floor_area" name="floor_area" type="number" step="0.01" value="{{ old('floor_area', $property->floor_area) }}" class="tt-input">
    </div>

    <div class="tt-field">
        <label for="lot_area">Lot area</label>
        <input id="lot_area" name="lot_area" type="number" step="0.01" value="{{ old('lot_area', $property->lot_area) }}" class="tt-input">
    </div>

    @if (auth()->user()->isAdmin())
        <div class="tt-field">
            <label for="listing_status">Listing status</label>
            <select id="listing_status" name="listing_status" class="tt-select">
                @foreach (['pending', 'approved', 'rejected', 'available', 'under_offer', 'sold', 'inactive'] as $status)
                    <option value="{{ $status }}" @selected(old('listing_status', $property->listing_status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="tt-field tt-span-2">
        <label for="amenity_ids">Existing amenities</label>
        <select id="amenity_ids" name="amenity_ids[]" class="tt-select" multiple size="5">
            @foreach ($amenities as $amenity)
                <option value="{{ $amenity->id }}" @selected(collect(old('amenity_ids', $property->amenities->pluck('id') ?? []))->contains($amenity->id))>
                    {{ $amenity->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="tt-field tt-span-2">
        <label for="new_amenities">New amenities</label>
        <input id="new_amenities" name="new_amenities" value="{{ old('new_amenities') }}" class="tt-input" placeholder="Pool, Garage, Garden">
        <p class="tt-meta">Add new amenity names separated by commas.</p>
    </div>

    <div class="tt-span-2">
        <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">{{ $editing ? 'Update listing' : 'Create listing' }}</button>
    </div>
</div>
