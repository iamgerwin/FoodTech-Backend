<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('food_chain_id');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('banner_image', 255)->nullable();
            $table->string('cuisine_type', 100)->nullable();
            $table->integer('average_prep_time')->default(30);
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('service_charge_percentage', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('food_chain_id')->references('id')->on('food_chains')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
