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
        Schema::create('order_items', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign key to the 'orders' table (with cascade on delete)
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // Foreign key to the 'products' table (with cascade on delete)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->string('name'); // Product name
            $table->integer('qty'); // Quantity
            $table->double('price', 10, 2); // Unit price
            $table->double('total', 10, 2); // Total price for this item
            
            // Timestamps for record creation and last update
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
