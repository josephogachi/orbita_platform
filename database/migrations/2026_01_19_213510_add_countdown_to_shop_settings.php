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
    Schema::table('shop_settings', function (Blueprint $table) {
        $table->string('promo_banner_text')->nullable(); // e.g. "Ramadan Sale"
        $table->dateTime('countdown_end')->nullable(); // Expiry date
        $table->boolean('show_countdown')->default(false);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            //
        });
    }
};
