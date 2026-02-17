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
    Schema::create('project_leads', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->label('Sales Agent'); // The agent who owns this
        $table->string('hotel_name');
        $table->string('client_name');
        $table->string('client_phone')->unique(); // Unique to prevent duplicates
        $table->string('client_email')->nullable();
        $table->enum('facility_type', ['hotel', 'apartment', 'school', 'hospital', 'residence']);
        $table->integer('number_of_rooms')->nullable();
        $table->json('interested_products')->nullable(); // Stores product IDs
        $table->enum('status', ['pending', 'contacted', 'survey_scheduled', 'ongoing', 'completed', 'lost'])->default('pending');
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_leads');
    }
};
