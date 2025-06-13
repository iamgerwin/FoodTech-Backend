<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branch_menu_item_variant_overrides', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('menu_item_variant_id');
            $table->string('custom_name')->nullable();
            $table->decimal('custom_price_modifier', 10, 2)->nullable();
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('restaurant_branches')->onDelete('cascade');
            $table->foreign('menu_item_variant_id')->references('id')->on('menu_item_variants')->onDelete('cascade');
            $table->unique(['branch_id', 'menu_item_variant_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('branch_menu_item_variant_overrides');
    }
};
