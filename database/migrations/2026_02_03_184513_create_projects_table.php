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
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // e.g., "Sarova Stanley Hotel Upgrade"
        $table->string('slug')->unique();
        $table->string('client_name')->nullable();
        $table->string('location')->nullable(); // e.g., "Nairobi CBD"
        $table->string('service_category')->nullable(); // e.g., "Hotel Locks & Minibars"
        $table->text('description')->nullable();
        $table->date('completion_date')->nullable();
        
        // Media
        $table->string('thumbnail_image')->nullable();
        $table->json('gallery_images')->nullable(); // For detailed project shots
        
        // Status
        $table->boolean('is_featured')->default(false); // Show on Homepage?
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
