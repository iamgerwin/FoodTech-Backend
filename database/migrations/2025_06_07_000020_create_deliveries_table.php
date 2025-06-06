<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('order_id');
            $table->uuid('driver_id');
            $table->string('status', 50)->default('pending');
            $table->text('pickup_address');
            $table->decimal('pickup_latitude', 10, 8)->nullable();
            $table->decimal('pickup_longitude', 11, 8)->nullable();
            $table->text('delivery_address');
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('pickup_estimated_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivery_estimated_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->decimal('delivery_fee', 8, 2);
            $table->decimal('driver_earning', 8, 2);
            $table->decimal('platform_commission', 8, 2);
            $table->text('delivery_instructions')->nullable();
            $table->string('proof_of_delivery', 255)->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
