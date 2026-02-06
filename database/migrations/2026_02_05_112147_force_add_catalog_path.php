<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 🟢 FORCE FIX: Only add if missing
        if (!Schema::hasColumn('shop_settings', 'catalog_path')) {
            Schema::table('shop_settings', function (Blueprint $table) {
                $table->string('catalog_path')->nullable()->after('about_image_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shop_settings', 'catalog_path')) {
            Schema::table('shop_settings', function (Blueprint $table) {
                $table->dropColumn('catalog_path');
            });
        }
    }
};