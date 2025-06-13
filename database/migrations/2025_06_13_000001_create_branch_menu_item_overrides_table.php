<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branch_menu_item_overrides', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('menu_item_id');
            $table->string('custom_name')->nullable();
            $table->decimal('custom_price', 10, 2)->nullable();
            $table->text('custom_description')->nullable();
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('restaurant_branches')->onDelete('cascade');
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->unique(['branch_id', 'menu_item_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('branch_menu_item_overrides');
    }
};
