<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithRoles;
use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PropertyController extends Controller
{
    use InteractsWithRoles;

    public function publicIndex(Request $request): View
    {
        $properties = Property::with(['agent', 'amenities'])
            ->whereIn('listing_status', ['approved', 'available', 'under_offer', 'sold'])
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('title', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('property_type', 'like', "%{$search}%");
                });
            })
            ->when($request->string('city')->toString(), fn ($query, $city) => $query->where('city', 'like', "%{$city}%"))
            ->when($request->string('property_type')->toString(), fn ($query, $type) => $query->where('property_type', $type))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->min_price))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->max_price))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.properties.index', [
            'properties' => $properties,
            'propertyTypes' => Property::query()->select('property_type')->distinct()->orderBy('property_type')->pluck('property_type'),
        ]);
    }

    public function publicShow(Property $property): View
    {
        abort_unless(in_array($property->listing_status, ['approved', 'available', 'under_offer', 'sold'], true), 404);

        return view('public.properties.show', [
            'property' => $property->load(['agent', 'amenities']),
        ]);
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $properties = Property::with(['agent', 'amenities'])
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('title', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('property_type', 'like', "%{$search}%");
                });
            })
            ->when($request->string('city')->toString(), fn ($query, $city) => $query->where('city', 'like', "%{$city}%"))
            ->when($request->string('property_type')->toString(), fn ($query, $type) => $query->where('property_type', $type))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->min_price))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->max_price))
            ->when($request->filled('bedrooms'), fn ($query) => $query->where('bedrooms', '>=', $request->bedrooms))
            ->when($request->string('listing_status')->toString(), fn ($query, $status) => $query->where('listing_status', $status))
            ->when($user->isAgent(), fn ($query) => $query->where('agent_id', $user->id))
            ->when($user->isClient(), fn ($query) => $query->whereIn('listing_status', ['approved', 'available', 'under_offer', 'sold']))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('properties.index', [
            'properties' => $properties,
            'propertyTypes' => Property::query()->select('property_type')->distinct()->orderBy('property_type')->pluck('property_type'),
            'statusOptions' => ['pending', 'approved', 'rejected', 'available', 'under_offer', 'sold', 'inactive'],
        ]);
    }

    public function create(Request $request): View
    {
        $this->requireRole($request, ['agent', 'admin']);

        return view('properties.create', [
            'property' => new Property(),
            'amenities' => Amenity::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->requireRole($request, ['agent', 'admin']);

        $validated = $this->validateProperty($request);
        $validated['agent_id'] = $request->user()->isAdmin() && $request->filled('agent_id') ? (int) $request->agent_id : $request->user()->id;
        $validated['listing_status'] = $request->user()->isAdmin() ? ($validated['listing_status'] ?? 'approved') : 'pending';

        $property = Property::create($validated);

        $this->syncAmenities($request, $property);

        return redirect()->route('properties.show', $property)->with('status', 'Property listing created successfully.');
    }

    public function show(Request $request, Property $property): View
    {
        $property->load(['agent', 'amenities', 'inquiries.client', 'viewings.client', 'offers.client', 'offers.tracking']);

        if ($request->user()->isClient()) {
            abort_unless(in_array($property->listing_status, ['approved', 'available', 'under_offer', 'sold'], true), 403);
        }

        if ($request->user()->isAgent()) {
            abort_unless($property->agent_id === $request->user()->id, 403);
        }

        return view('properties.show', compact('property'));
    }

    public function edit(Request $request, Property $property): View
    {
        $this->ensurePropertyAccess($request, $property);

        return view('properties.edit', [
            'property' => $property->load('amenities'),
            'amenities' => Amenity::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $this->ensurePropertyAccess($request, $property);

        $validated = $this->validateProperty($request, $property->id);

        if ($request->user()->isAgent()) {
            $validated['listing_status'] = 'pending';
            $validated['approved_by'] = null;
            $validated['approved_at'] = null;
        }

        $property->update($validated);
        $this->syncAmenities($request, $property);

        return redirect()->route('properties.show', $property)->with('status', 'Property updated successfully.');
    }

    public function approve(Request $request, Property $property): RedirectResponse
    {
        $this->requireRole($request, ['admin']);

        $status = $request->validate([
            'listing_status' => ['required', 'in:approved,rejected,available,inactive,sold,under_offer'],
        ])['listing_status'];

        $property->update([
            'listing_status' => $status,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Listing status updated.');
    }

    protected function validateProperty(Request $request, ?int $propertyId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'property_type' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'numeric', 'min:0'],
            'floor_area' => ['nullable', 'numeric', 'min:0'],
            'lot_area' => ['nullable', 'numeric', 'min:0'],
            'listing_status' => ['nullable', 'in:pending,approved,rejected,available,under_offer,sold,inactive'],
            'is_featured' => ['nullable', 'boolean'],
            'amenity_ids' => ['nullable', 'array'],
            'amenity_ids.*' => ['integer', 'exists:amenities,id'],
            'new_amenities' => ['nullable', 'string'],
        ]);
    }

    protected function syncAmenities(Request $request, Property $property): void
    {
        $amenityIds = collect($request->input('amenity_ids', []))->map(fn ($id) => (int) $id)->filter()->values();

        collect(explode(',', (string) $request->input('new_amenities')))
            ->map(fn ($amenity) => trim($amenity))
            ->filter()
            ->each(function ($name) use ($amenityIds) {
                $amenity = Amenity::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );

                $amenityIds->push($amenity->id);
            });

        $property->amenities()->sync($amenityIds->unique()->all());
    }

    protected function ensurePropertyAccess(Request $request, Property $property): void
    {
        abort_unless(
            $request->user()->isAdmin() || ($request->user()->isAgent() && $property->agent_id === $request->user()->id),
            403
        );
    }
}
