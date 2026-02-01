<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        // Only add old_price if it doesn't exist
        if (!Schema::hasColumn('products', 'old_price')) {
            $table->decimal('old_price', 12, 2)->nullable();
        }

        // Only add stock_quantity if it doesn't exist
        if (!Schema::hasColumn('products', 'stock_quantity')) {
            $table->integer('stock_quantity')->default(0);
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
