<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('avatar_url')->nullable();
            $table->enum('department', ['administration', 'groundskeeping', 'security', 'medical']);
            $table->string('role_title');
            $table->string('shift');
            $table->string('matchday_duty')->nullable();
            $table->enum('status', ['on_shift', 'off_duty'])->default('off_duty');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
