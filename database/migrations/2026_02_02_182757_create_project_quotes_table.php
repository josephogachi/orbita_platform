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
    Schema::create('project_quotes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained();
        $table->string('hotel_name')->nullable();
        $table->string('property_type'); // Hotel, Hospital, etc.
        $table->string('location_type'); // Shipping Zone or "Other"
        $table->string('exact_location')->nullable();
        $table->string('phone_number');
        
        // Project Details
        $table->integer('unit_count'); // Number of doors/rooms
        $table->string('door_type')->nullable(); // Wood, Aluminum, etc.
        $table->string('door_image')->nullable();
        $table->string('project_status'); // Ongoing, New, etc.
        $table->boolean('wants_installation')->default(false);
        
        // Financials
        $table->string('payment_plan'); // Installments vs One-time
        $table->decimal('estimated_total', 12, 2)->nullable();
        $table->string('status')->default('pending'); // pending, drafted, sent
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_quotes');
    }
};
