<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update Products Table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_hot')) {
                $table->boolean('is_hot')->default(false);
            }
            if (!Schema::hasColumn('products', 'is_sponsored')) {
                $table->boolean('is_sponsored')->default(false);
            }
            if (!Schema::hasColumn('products', 'affiliate_link')) {
                $table->string('affiliate_link')->nullable();
            }
            if (!Schema::hasColumn('products', 'discount_percent')) {
                $table->integer('discount_percent')->nullable();
            }
        });

        // Update Shop Settings Table
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

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_hot', 'is_sponsored', 'affiliate_link', 'discount_percent']);
        });
        Schema::table('shop_settings', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'phone_contact', 'email_contact']);
        });
    }
};