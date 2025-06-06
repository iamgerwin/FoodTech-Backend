<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('coupon_id');
            $table->uuid('order_id');
            $table->uuid('customer_id');
            $table->decimal('discount_amount', 8, 2);
            $table->timestamp('used_at')->useCurrent();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
