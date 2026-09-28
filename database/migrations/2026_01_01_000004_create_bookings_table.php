<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ground_id')->constrained()->cascadeOnDelete();
            $table->foreignId('slot_id')->constrained('time_slots')->cascadeOnDelete();
            $table->foreignId('player_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('booking_date');
            $table->uuid('transaction_uuid')->unique();
            $table->unsignedInteger('deposit_amount');
            $table->unsignedInteger('total_amount');
            $table->enum('status', ['pending', 'locked', 'confirmed', 'cancelled'])->default('pending');
            $table->enum('source', ['online', 'walk_in'])->default('online');
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();

            $table->index(['venue_id', 'booking_date']);
            $table->index(['transaction_uuid', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};