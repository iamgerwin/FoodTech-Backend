<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('restaurant_id');
            $table->uuid('category_id');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->integer('preparation_time')->default(15);
            $table->integer('calories')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('allergens')->nullable();
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_gluten_free')->default(false);
            $table->boolean('is_spicy')->default(false);
            $table->integer('spice_level')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['tenant_id', 'restaurant_id', 'slug']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('menu_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
