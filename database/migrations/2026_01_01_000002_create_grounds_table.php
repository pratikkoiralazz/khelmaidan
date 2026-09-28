<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('ground_name');
            $table->enum('type', ['5v5', '7v7', '9v9', 'badminton', 'cricket_turf']);
            $table->boolean('is_indoor')->default(true);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['venue_id', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grounds');
    }
};