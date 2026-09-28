<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('custom_domain')->nullable()->unique();
            $table->json('theme_settings')->nullable();
            $table->string('esewa_merchant_code')->default('EPAYTEST');
            $table->text('esewa_secret_key')->nullable();
            $table->text('khalti_secret_key')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('custom_domain');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};