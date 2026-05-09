<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('property_type');
            $table->decimal('price', 15, 2);
            $table->string('address');
            $table->string('city');
            $table->string('province')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->decimal('bathrooms', 4, 1)->nullable();
            $table->decimal('floor_area', 10, 2)->nullable();
            $table->decimal('lot_area', 10, 2)->nullable();
            $table->enum('listing_status', ['pending', 'approved', 'rejected', 'available', 'under_offer', 'sold', 'inactive'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'responded', 'closed'])->default('new');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('viewings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('scheduled_at');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('offer_amount', 15, 2);
            $table->text('message')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'accepted', 'rejected', 'withdrawn'])->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->unique()->constrained('offers')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('final_price', 15, 2);
            $table->enum('status', ['processing', 'completed', 'cancelled'])->default('processing');
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('amenity_property', function (Blueprint $table) {
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
            $table->primary(['property_id', 'amenity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_property');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('trackings');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('viewings');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
    }
};
