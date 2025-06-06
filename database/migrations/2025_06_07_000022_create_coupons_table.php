<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('restaurant_id')->nullable();
            $table->string('code', 100)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('type', 50);
            $table->decimal('value', 8, 2);
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->decimal('maximum_discount_amount', 8, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_limit_per_customer')->default(1);
            $table->integer('current_usage')->default(0);
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
