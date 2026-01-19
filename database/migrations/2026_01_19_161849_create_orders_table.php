<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // User link (nullable because POS walk-in customers might not have an account)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('order_number')->unique();
            
            // Statuses
            $table->enum('status', ['new', 'processing', 'shipped', 'delivered', 'cancelled'])->default('new');
            $table->enum('payment_status', ['paid', 'unpaid', 'partial'])->default('unpaid');
            $table->string('payment_method')->nullable(); // e.g. 'stripe', 'mpesa', 'cash'
            
            // Financials
            $table->string('currency')->default('KES');
            $table->decimal('sub_total', 10, 2)->default(0);
            $table->decimal('vat', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            
            // Delivery Info
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->string('shipping_method')->nullable();
            $table->text('shipping_address')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};