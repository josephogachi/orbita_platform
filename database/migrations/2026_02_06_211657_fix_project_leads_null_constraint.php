<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Making facility_type nullable to prevent Integrity Constraint Violations.
     */
    public function up(): void
    {
        Schema::table('project_leads', function (Blueprint $table) {
            // Check if column exists, then modify it to be nullable
            if (Schema::hasColumn('project_leads', 'facility_type')) {
                $table->string('facility_type')->nullable()->change();
            } else {
                // If the column was completely missing, add it now as nullable
                $table->string('facility_type')->nullable()->after('hotel_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_leads', function (Blueprint $table) {
            $table->string('facility_type')->nullable(false)->change();
        });
    }
};