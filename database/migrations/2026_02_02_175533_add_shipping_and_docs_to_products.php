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
        // Only add weight if it doesn't exist
        if (!Schema::hasColumn('products', 'weight')) {
            $table->decimal('weight')->default(1);
        }
        
        // Add any other columns in this file using the same 'if' check
        // For example, if there is a 'dimensions' column:
        if (!Schema::hasColumn('products', 'dimensions')) {
            $table->string('dimensions')->nullable();
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
