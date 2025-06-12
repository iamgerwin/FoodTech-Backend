<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Enums\PaymentTypeEnum;

return new class extends Migration
{
    public function up()
    {
        // 1. First, add the new columns as nullable
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_transactions', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('tenant_id');
            }
            if (!Schema::hasColumn('payment_transactions', 'payment_subtype')) {
                $table->string('payment_subtype')->nullable()->after('payment_type');
            }
        });

        // 2. Rename payment_method to payment_details if it exists
        if (Schema::hasColumn('payment_transactions', 'payment_method')) {
            Schema::table('payment_transactions', function (Blueprint $table) {
                $table->renameColumn('payment_method', 'payment_details');
            });
        }

        // 3. Set default values for existing records
        DB::table('payment_transactions')
            ->whereNull('payment_type')
            ->update([
                'payment_type' => PaymentTypeEnum::CASH->value,
                'payment_subtype' => null,
                'payment_details' => DB::raw("COALESCE(payment_details::text, '{}'::text)")
            ]);

        // 4. Change payment_details to JSON type if it's not already
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('payment_transactions', 'payment_details')) {
                $table->json('payment_details')->nullable()->change();
            }
        });

        // 5. Finally, make payment_type required
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->string('payment_type')->nullable(false)->change();
        });
    }

    public function down()
    {
        // This is a one-way migration for fixing the schema
        // If you need to rollback, you should create a new migration
    }
};
