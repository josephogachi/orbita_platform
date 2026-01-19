<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            
            $table->string('name'); // Company Name or Person Name
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            
            // Business Details
            $table->string('tax_pin')->nullable(); // KRA PIN
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};