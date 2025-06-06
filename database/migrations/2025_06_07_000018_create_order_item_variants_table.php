<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('variant_id');
            $table->string('name', 255);
            $table->decimal('price_modifier', 8, 2);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('menu_item_variants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_variants');
    }
};
