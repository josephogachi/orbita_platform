<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partnership_applications', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('kra_pin');
            $table->string('business_type');
            $table->string('years_active');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->string('physical_address');
            $table->string('region');
            $table->string('team_size');
            $table->text('proposal');
            $table->string('status')->default('Pending Review');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_applications');
    }
};