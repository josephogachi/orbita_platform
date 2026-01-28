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
    Schema::create('shop_settings', function (Blueprint $table) {
        $table->id();
        // Branding & Contact
        $table->string('shop_name')->default('Orbita Solutions');
        $table->string('email_contact')->default('info@orbitakenya.com');
        $table->string('phone_contact')->default('+254 726 777 733');
        $table->string('shop_address')->nullable(); // Showroom
        $table->string('office_address')->nullable(); // Corporate Office
        $table->string('logo_path')->nullable();
        
        // Banking (For Invoices)
        $table->string('bank_name')->default('CO-OPERATIVE BANK');
        $table->string('account_name')->default('ORBITAHTECH SYSTEMS KENYA LTD.');
        $table->string('account_number')->default('01100542859001');
        
        // Marketing/Countdown
        $table->boolean('show_countdown')->default(false);
        $table->string('promo_banner_text')->nullable();
        $table->datetime('countdown_end')->nullable();
        
        // Financials
        $table->integer('vat_percentage')->default(16);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
