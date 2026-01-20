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
    // Update Products for "Hot", "New", "Sponsored"
    Schema::table('products', function (Blueprint $table) {
        $table->boolean('is_hot')->default(false);
        $table->boolean('is_sponsored')->default(false);
        $table->string('affiliate_link')->nullable(); // For sponsored items
        $table->integer('discount_percent')->nullable();
    });

    // Update Shop Settings for Logo & Countdown
    Schema::table('shop_settings', function (Blueprint $table) {
        $table->string('logo_path')->nullable();
        $table->string('phone_contact')->nullable();
        $table->string('email_contact')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
