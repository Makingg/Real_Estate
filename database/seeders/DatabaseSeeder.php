<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'role' => 'admin',
                'phone' => '09170000000',
                'address' => 'TirahanTech HQ',
                'password' => 'password123',
                'is_active' => true,
            ]
        );

        collect(['Pool', 'Garage', 'Garden', 'Balcony', 'Security', 'Parking'])
            ->each(fn ($name) => Amenity::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            ));
    }
}
