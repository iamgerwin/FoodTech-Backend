<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('user_id');
            $table->string('license_number', 100)->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('vehicle_type', 50)->nullable();
            $table->string('vehicle_plate', 20)->nullable();
            $table->string('vehicle_model', 100)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('last_location_update')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_deliveries')->default(0);
            $table->decimal('total_earnings', 12, 2)->default(0);
            $table->timestamps();
            $table->unique(['tenant_id', 'user_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
