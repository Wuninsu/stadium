<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('jersey_number');
            $table->string('name');
            $table->string('avatar_url')->nullable();
            $table->string('position');
            $table->unsignedTinyInteger('age');
            $table->string('nationality');
            $table->enum('fitness_status', ['fit', 'injured'])->default('fit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
