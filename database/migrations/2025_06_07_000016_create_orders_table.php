<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('order_number', 50)->unique();
            $table->uuid('customer_id');
            $table->uuid('restaurant_id');
            $table->uuid('branch_id');
            $table->uuid('delivery_address_id');
            $table->string('status', 50)->default('pending');
            $table->string('order_type', 50)->default('delivery');
            $table->string('payment_status', 50)->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('service_charge', 8, 2)->default(0);
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->integer('estimated_prep_time')->nullable();
            $table->timestamp('estimated_delivery_time')->nullable();
            $table->timestamp('placed_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('special_instructions')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('restaurant_branches')->onDelete('cascade');
            $table->foreign('delivery_address_id')->references('id')->on('customer_addresses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
