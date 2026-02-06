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
        // 1. Update Products Table (Safely)
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'is_hot')) {
                    $table->boolean('is_hot')->default(false);
                }
                if (!Schema::hasColumn('products', 'is_sponsored')) {
                    $table->boolean('is_sponsored')->default(false);
                }
                if (!Schema::hasColumn('products', 'affiliate_link')) {
                    $table->string('affiliate_link')->nullable(); // For external products
                }
                if (!Schema::hasColumn('products', 'discount_percent')) {
                    $table->integer('discount_percent')->nullable();
                }
            });
        }

        // 2. Update Shop Settings Table
        // 🛑 SKIP LOGIC: We check if the table exists first.
        // If the 'shop_settings' table doesn't exist yet (because the creation file is dated Jan 28th),
        // we skip this block safely instead of crashing.
        if (Schema::hasTable('shop_settings')) {
            Schema::table('shop_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('shop_settings', 'logo_path')) {
                    $table->string('logo_path')->nullable();
                }
                if (!Schema::hasColumn('shop_settings', 'phone_contact')) {
                    $table->string('phone_contact')->nullable();
                }
                if (!Schema::hasColumn('shop_settings', 'email_contact')) {
                    $table->string('email_contact')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn(['is_hot', 'is_sponsored', 'affiliate_link', 'discount_percent']);
            });
        }

        if (Schema::hasTable('shop_settings')) {
            Schema::table('shop_settings', function (Blueprint $table) {
                $table->dropColumn(['logo_path', 'phone_contact', 'email_contact']);
            });
        }
    }
};