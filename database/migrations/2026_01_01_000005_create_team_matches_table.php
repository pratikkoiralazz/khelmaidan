<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('host_player_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('opponent_player_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('host_team_name');
            $table->string('opponent_team_name')->nullable();
            $table->enum('opponent_status', ['open', 'matched', 'closed'])->default('open');
            $table->unsignedInteger('split_fee_per_team');
            $table->timestamps();

            $table->index(['opponent_status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_matches');
    }
};