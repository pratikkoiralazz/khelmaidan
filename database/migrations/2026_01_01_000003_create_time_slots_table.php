<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ground_id')->constrained()->cascadeOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('base_price');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['ground_id', 'start_time', 'end_time'], 'unique_ground_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};