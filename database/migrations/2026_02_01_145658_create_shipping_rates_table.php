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
    Schema::create('shipping_rates', function (Blueprint $table) {
        $table->id();
        $table->foreignId('shipping_zone_id')->constrained()->cascadeOnDelete();
        $table->decimal('weight_min', 8, 2)->default(0); // e.g., 0 kg
        $table->decimal('weight_max', 8, 2)->nullable(); // e.g., 5 kg
        $table->integer('cost'); // e.g., 250 KES
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
