<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'address',
        'license_number',
        'is_active',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function managedProperties(): HasMany
    {
        return $this->hasMany(Property::class, 'agent_id');
    }

    public function approvedProperties(): HasMany
    {
        return $this->hasMany(Property::class, 'approved_by');
    }

    public function inquiriesSent(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'client_id');
    }

    public function inquiriesReceived(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'agent_id');
    }

    public function viewingsAsClient(): HasMany
    {
        return $this->hasMany(Viewing::class, 'client_id');
    }

    public function viewingsAsAgent(): HasMany
    {
        return $this->hasMany(Viewing::class, 'agent_id');
    }

    public function offersMade(): HasMany
    {
        return $this->hasMany(Offer::class, 'client_id');
    }

    public function offersReceived(): HasMany
    {
        return $this->hasMany(Offer::class, 'agent_id');
    }

    public function purchasedTrackings(): HasMany
    {
        return $this->hasMany(Tracking::class, 'buyer_id');
    }

    public function processedTrackings(): HasMany
    {
        return $this->hasMany(Tracking::class, 'processed_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}
