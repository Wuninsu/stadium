<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('organisation')->nullable()->after('email');
            $table->string('phone')->nullable()->after('organisation');
            $table->enum('role', ['admin', 'facilities_manager', 'ticketing_lead', 'customer'])->default('customer')->after('phone');
            $table->enum('access_level', ['full', 'bookings_only', 'facilities_only'])->nullable()->after('role');
            $table->string('avatar_url')->nullable()->after('access_level');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'organisation', 'phone', 'role', 'access_level', 'avatar_url']);
        });
    }
};
