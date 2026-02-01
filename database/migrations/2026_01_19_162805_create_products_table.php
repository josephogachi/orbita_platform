<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            
            // Core Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable(); // Stock Keeping Unit
            $table->text('description')->nullable();
            $table->longText('technical_specs')->nullable(); // Good for Locks/Hospitality items
            
            // Pricing
            $table->decimal('price', 10, 2); // Retail Price
            $table->decimal('wholesale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable(); 
            
            // Inventory
            $table->integer('stock_quantity')->default(0);
            $table->integer('alert_quantity')->default(5);
            
            // Status & Media
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};