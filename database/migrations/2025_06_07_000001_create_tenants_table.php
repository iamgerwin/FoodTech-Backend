<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('data')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('slug', 255)->unique()->nullable();
            $table->string('domain', 255)->unique()->nullable();
            $table->string('database', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
