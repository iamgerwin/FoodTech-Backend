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
            ]);

        // 4. Ensure all payment_details are valid JSON, then convert to JSONB using the USING clause
        if (Schema::hasColumn('payment_transactions', 'payment_details')) {
            // Set NULL or empty to '{}'
            DB::statement("UPDATE payment_transactions SET payment_details = '{}' WHERE payment_details IS NULL OR payment_details = ''");
            // Set all non-object/array JSON to '{}'
            DB::statement("UPDATE payment_transactions SET payment_details = '{}' WHERE payment_details IS NOT NULL AND payment_details <> '' AND NOT (payment_details ~ '^(\\s*\\{.*\\}\\s*|\\s*\\[.*\\]\\s*)$')");
            // Now safely cast to JSONB
            DB::statement('ALTER TABLE payment_transactions ALTER COLUMN payment_details TYPE JSONB USING payment_details::jsonb');
            DB::statement('ALTER TABLE payment_transactions ALTER COLUMN payment_details DROP NOT NULL');
        }

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
