<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_add_on_menu_item', function (Blueprint $table) {
            $table->uuid('menu_item_id');
            $table->uuid('menu_add_on_id');
            $table->primary(['menu_item_id', 'menu_add_on_id']);
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->foreign('menu_add_on_id')->references('id')->on('menu_add_ons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_add_on_menu_item');
    }
};
