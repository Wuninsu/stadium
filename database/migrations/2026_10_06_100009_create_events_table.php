<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['league_fixture', 'athletics', 'tournament', 'youth', 'community']);
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('expected_attendance')->nullable();
            $table->unsignedInteger('tickets_sold')->default(0);
            $table->decimal('ticket_price', 10, 2)->nullable();
            $table->enum('status', ['open', 'selling_fast', 'sold_out', 'confirmed', 'prep'])->default('prep');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
