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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('property_type_id');
            $table->unsignedBigInteger('city_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('address');
            $table->integer('bathrooms');
            $table->integer('bedrooms');
            $table->integer('area');
            $table->text('amenities');
            $table->boolean('status');
            $table->integer('views')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('property_type_id')->references('id')->on('property_types')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
