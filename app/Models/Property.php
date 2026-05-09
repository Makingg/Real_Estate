<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $fillable = [
        'agent_id',
        'approved_by',
        'title',
        'description',
        'property_type',
        'price',
        'address',
        'city',
        'province',
        'postal_code',
        'bedrooms',
        'bathrooms',
        'floor_area',
        'lot_area',
        'listing_status',
        'approved_at',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'bathrooms' => 'decimal:1',
            'floor_area' => 'decimal:2',
            'lot_area' => 'decimal:2',
            'approved_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function viewings(): HasMany
    {
        return $this->hasMany(Viewing::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function getListingStatusLabelAttribute(): string
    {
        return $this->listing_status === 'under_offer'
            ? 'Offer Accepted'
            : ucfirst(str_replace('_', ' ', $this->listing_status));
    }
}
