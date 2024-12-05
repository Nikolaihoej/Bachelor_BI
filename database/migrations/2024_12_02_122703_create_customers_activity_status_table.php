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
        // Drop the table if it exists
        Schema::dropIfExists('customer_activity_status');

        // Create the table
        Schema::create('customer_activity_status', function (Blueprint $table) {
            $table->id('ActivityStatusID'); // Autoincrement as primary key
            $table->integer('MemberSinceMonths');
            $table->boolean('HasTrainedLastMonth');
            $table->integer('DaysSinceLastVisit');
            $table->integer('TrainingSessionsThisMonth');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_activity_status');
    }
};