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
        Schema::table('products', function (Blueprint $table) {
            // Add 'short_description' column after 'description' column
            $table->text('short_description')->after('description')->nullable();

            // Add 'shipping_returns' column after 'short_description' column
            $table->text('shipping_returns')->after('short_description')->nullable();

            // Add 'related_products' column after 'shipping_returns' column
            $table->text('related_products')->after('shipping_returns')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop 'short_description' column
            $table->dropColumn('short_description');
            
            // Drop 'shipping_returns' column
            $table->dropColumn('shipping_returns');
            
            // Drop 'related_products' column
            $table->dropColumn('related_products');
        });
    }
};
