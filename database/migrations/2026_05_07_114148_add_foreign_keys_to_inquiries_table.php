<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    private function foreignExists(string $table, string $constraint): bool
    {
        return !empty(\Illuminate\Support\Facades\DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$table, $constraint]
        ));
    }

    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!$this->foreignExists('inquiries', 'inquiries_property_id_foreign')) {
                $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            }
            if (!$this->foreignExists('inquiries', 'inquiries_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if ($this->foreignExists('inquiries', 'inquiries_property_id_foreign')) {
                $table->dropForeign(['property_id']);
            }
            if ($this->foreignExists('inquiries', 'inquiries_user_id_foreign')) {
                $table->dropForeign(['user_id']);
            }
        });
    }
};
