<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function foreignExists(string $table, string $constraint): bool
    {
        return !empty(DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$table, $constraint]
        ));
    }

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!$this->foreignExists('users', 'users_province_id_foreign')) {
                $table->foreign('province_id')->references('id')->on('provinces')->nullOnDelete();
            }
            if (!$this->foreignExists('users', 'users_city_id_foreign')) {
                $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
            }
            if (!$this->foreignExists('users', 'users_barangay_id_foreign')) {
                $table->foreign('barangay_id')->references('id')->on('barangays')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if ($this->foreignExists('users', 'users_province_id_foreign')) {
                $table->dropForeign(['province_id']);
            }
            if ($this->foreignExists('users', 'users_city_id_foreign')) {
                $table->dropForeign(['city_id']);
            }
            if ($this->foreignExists('users', 'users_barangay_id_foreign')) {
                $table->dropForeign(['barangay_id']);
            }
        });
    }
};
