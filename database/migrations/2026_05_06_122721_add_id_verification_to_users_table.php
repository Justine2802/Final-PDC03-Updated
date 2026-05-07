<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('bio');
            $table->string('full_address')->nullable()->after('date_of_birth');
            $table->string('id_type')->nullable()->after('full_address');
            $table->string('id_number')->nullable()->after('id_type');
            $table->enum('id_verification_status', ['none', 'pending', 'verified', 'rejected'])
                ->default('none')
                ->after('id_number');
            $table->text('id_rejection_reason')->nullable()->after('id_verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'full_address',
                'id_type',
                'id_number',
                'id_verification_status',
                'id_rejection_reason',
            ]);
        });
    }
};
