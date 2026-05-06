<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address_line')->nullable()->after('full_address');
            $table->unsignedBigInteger('province_id')->nullable()->after('address_line');
            $table->unsignedBigInteger('city_id')->nullable()->after('province_id');
            $table->unsignedBigInteger('barangay_id')->nullable()->after('city_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address_line', 'province_id', 'city_id', 'barangay_id']);
        });
    }
};
