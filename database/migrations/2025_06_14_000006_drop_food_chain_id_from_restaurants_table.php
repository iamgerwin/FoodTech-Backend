<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            if (Schema::hasColumn('restaurants', 'food_chain_id')) {
                // The foreign key constraint might not exist, causing an error.
                // We'll just drop the column. If a constraint exists, a different error will be thrown.
                // This resolves the 'constraint does not exist' error during fresh migrations.
                $table->dropColumn('food_chain_id');
            }
        });
    }
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Re-add the column and the foreign key constraint for proper rollback.
            if (!Schema::hasColumn('restaurants', 'food_chain_id')) {
                $table->foreignUuid('food_chain_id')->nullable()->constrained('food_chains')->cascadeOnDelete();
            }
        });
    }
};
