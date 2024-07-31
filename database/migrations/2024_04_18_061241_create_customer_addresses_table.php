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
        Schema::create('customer_addresses', function (Blueprint $table) {
             // Primary key
           $table->id();
            
           // Foreign key to the 'users' table (with cascade on delete)
           $table->foreignId('user_id')->constrained()->onDelete('cascade');
    
           // Customer information
           $table->string('first_name'); // Customer's first name
           $table->string('last_name'); // Customer's last name
           $table->string('email'); // Customer's email address
           $table->string('mobile'); // Customer's mobile phone number
           
           // Foreign key to the 'countries' table (with cascade on delete)
           $table->foreignId('country_id')->constrained()->onDelete('cascade');
           
           // Customer's shipping address details
           $table->text('address'); // Shipping address
           $table->string('apartment')->nullable(); // Apartment or unit number (if any)
           $table->string('city'); // City name
           $table->string('state'); // State or region name
           $table->string('zip'); // Postal code or zip code
           
           // Timestamps for record creation and last update
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
