<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('order_id');
            $table->string('transaction_id', 255)->unique();
            $table->string('payment_method', 50);
            $table->string('payment_provider', 100)->nullable();
            $table->string('provider_transaction_id', 255)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PHP');
            $table->string('status', 50)->default('pending');
            $table->json('gateway_response')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
