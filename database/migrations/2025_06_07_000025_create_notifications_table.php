<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('user_id');
            $table->string('title', 255);
            $table->text('message');
            $table->string('type', 50);
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
