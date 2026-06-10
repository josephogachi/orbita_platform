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
        Schema::create('logistics_products', function (Blueprint $table) {
            $table->id();
            
            // Core Info
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->integer('pcs_per_carton')->default(1);
            
            // Dimensions
            $table->enum('dimension_unit', ['cm', 'mm', 'in'])->default('cm');
            $table->decimal('carton_length', 10, 2)->nullable();
            $table->decimal('carton_width', 10, 2)->nullable();
            $table->decimal('carton_height', 10, 2)->nullable();
            
            // Weight
            $table->enum('weight_unit', ['kg', 'lbs'])->default('kg');
            $table->decimal('carton_gross_weight', 10, 2)->nullable();
            
            // Auto-Calculated Logistical Metrics
            $table->decimal('cbm_per_carton', 10, 6)->nullable();
            $table->decimal('cbm_per_piece', 10, 6)->nullable();
            $table->decimal('weight_per_piece', 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics_products');
    }
};
