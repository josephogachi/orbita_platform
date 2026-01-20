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
    Schema::create('side_ads', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // e.g., "Smart Lock V7"
        $table->string('subtitle')->nullable(); // e.g., "Upgrade security..."
        $table->string('badge_text')->nullable(); // e.g., "TRENDING"
        $table->string('button_text')->default('View Deal');
        $table->string('image_path');
        $table->string('link_url')->nullable();
        $table->boolean('is_active')->default(true);
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('side_ads');
    }
};
