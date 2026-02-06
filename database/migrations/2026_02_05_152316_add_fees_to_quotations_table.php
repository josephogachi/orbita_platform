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
    Schema::table('quotations', function (Blueprint $table) {
        if (!Schema::hasColumn('quotations', 'installation_fee')) {
            $table->decimal('installation_fee', 12, 2)->default(0);
        }
        if (!Schema::hasColumn('quotations', 'shipping_fee')) {
            $table->decimal('shipping_fee', 12, 2)->default(0);
        }
        if (!Schema::hasColumn('quotations', 'has_maintenance')) {
            $table->boolean('has_maintenance')->default(false);
        }
        if (!Schema::hasColumn('quotations', 'maintenance_fee')) {
            $table->decimal('maintenance_fee', 12, 2)->default(0);
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'installation_fee',
                'shipping_fee',
                'has_maintenance',
                'maintenance_fee'
            ]);
        });
    }
};