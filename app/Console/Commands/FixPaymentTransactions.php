<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentTypeEnum;

class FixPaymentTransactions extends Command
{
    protected $signature = 'fix:payment-transactions';
    protected $description = 'Fix payment transactions table structure';

    public function handle()
    {
        $this->info('Starting payment transactions fix...');

        // 1. Add columns if they don't exist
        $this->info('Adding/updating columns...');
        if (!Schema::hasColumn('payment_transactions', 'payment_type')) {
            Schema::table('payment_transactions', function ($table) {
                $table->string('payment_type')->nullable()->after('tenant_id');
            });
            $this->info('Added payment_type column');
        }

        if (!Schema::hasColumn('payment_transactions', 'payment_subtype')) {
            Schema::table('payment_transactions', function ($table) {
                $table->string('payment_subtype')->nullable()->after('payment_type');
            });
            $this->info('Added payment_subtype column');
        }

        // 2. Rename payment_method to payment_details if it exists
        if (Schema::hasColumn('payment_transactions', 'payment_method') && 
            !Schema::hasColumn('payment_transactions', 'payment_details')) {
            DB::statement('ALTER TABLE payment_transactions RENAME COLUMN payment_method TO payment_details');
            $this->info('Renamed payment_method to payment_details');
        }

        // 3. Set default values for existing records
        $this->info('Updating existing records...');
        DB::table('payment_transactions')
            ->whereNull('payment_type')
            ->update([
                'payment_type' => PaymentTypeEnum::CASH->value,
                'payment_subtype' => null,
            ]);
        
        // 4. Make payment_type required
        if (Schema::hasColumn('payment_transactions', 'payment_type')) {
            DB::statement('ALTER TABLE payment_transactions ALTER COLUMN payment_type SET NOT NULL');
            $this->info('Made payment_type required');
        }

        $this->info('Payment transactions fix completed successfully!');
        return 0;
    }
}
