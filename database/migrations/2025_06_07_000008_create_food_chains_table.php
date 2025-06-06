<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_chains', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('owner_id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('business_license', 255)->nullable();
            $table->string('tax_id', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_chains');
    }
};
