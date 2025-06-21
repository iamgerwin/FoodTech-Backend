<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('role_has_permissions')
            ->whereRaw("permission_id::text ~ '^[0-9]+$'")
            ->delete();
    }
    
    public function down()
    {
        // No rollback needed, as data is deleted
    }
};
