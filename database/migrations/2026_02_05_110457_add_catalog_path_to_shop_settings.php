<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 🟢 FIX: Check if column exists first to prevent "Duplicate Column" crash
        if (!Schema::hasColumn('shop_settings', 'about_image_path')) {
            Schema::table('shop_settings', function (Blueprint $table) {
                $table->string('about_image_path')->nullable()->after('logo_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shop_settings', 'about_image_path')) {
            Schema::table('shop_settings', function (Blueprint $table) {
                $table->dropColumn('about_image_path');
            });
        }
    }
};