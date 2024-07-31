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
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Discount code
            $table->string('name')->nullable(); // Discount coupon name
            $table->text('description')->nullable(); // Coupon description
            $table->integer('max_uses')->nullable(); // Max uses
            $table->integer('max_uses_user')->nullable(); // Max uses per user
            $table->enum('type',['percent', 'fixed'])->default('fixed'); // Type of discount (percent or fixed)
            $table->double('discount_amount', 10, 2); // Discount amount
            $table->double('min_amount', 10, 2)->nullable(); // 
            $table->integer('status')->default(1);
            $table->timestamp('starts_at')->nullable(); // Coupon start date and time
            $table->timestamp('expires_at')->nullable(); // Coupon expiry date and time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
