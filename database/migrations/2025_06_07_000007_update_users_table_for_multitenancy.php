<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->after('id');
            $table->string('phone', 20)->nullable()->after('password');
            $table->string('avatar', 255)->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->string('user_type', 50)->after('is_active');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['tenant_id', 'phone', 'avatar', 'is_active', 'user_type']);
        });
    }
};
