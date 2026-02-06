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
    Schema::create('quotations', function (Blueprint $table) {
        $table->id();
        $table->string('quotation_number')->unique();
        $table->string('client_name');
        $table->string('client_email');
        $table->string('client_phone')->nullable();
        $table->string('hotel_name')->nullable();
        
        $table->json('items'); // Stores product_id, qty, price
        
        // 🟢 Add these missing columns:
        $table->decimal('installation_fee', 12, 2)->default(0);
        $table->decimal('shipping_fee', 12, 2)->default(0);
        $table->boolean('has_maintenance')->default(false);
        $table->decimal('maintenance_fee', 12, 2)->default(0);
        
        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('total', 12, 2)->default(0);
        
        $table->enum('status', ['pending', 'reviewed', 'sent', 'expired'])->default('pending');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
