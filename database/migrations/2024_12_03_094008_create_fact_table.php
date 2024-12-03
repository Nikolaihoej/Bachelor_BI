<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('fact_table');
        
        Schema::create('fact_table', function (Blueprint $table) {
            $table->id(); // Autoincrement for faktatabelens ID
            $table->unsignedBigInteger('CustomerID'); // ID refererer til `customers`
            $table->unsignedBigInteger('MembershipTypeID'); // Fremmednøgle refererer til `membership_types`
            $table->unsignedBigInteger('ActivityStatusID'); // Fremmednøgle refererer til `customer_activity_status`
    
            // Fremmednøgler
            $table->foreign('CustomerID')->references('CustomerID')->on('customers')->onDelete('cascade');
            $table->foreign('MembershipTypeID')->references('MembershipTypeID')->on('membership_types')->onDelete('cascade');
            $table->foreign('ActivityStatusID')->references('ActivityStatusID')->on('customer_activity_status')->onDelete('cascade');
    
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_table');
    }
};
