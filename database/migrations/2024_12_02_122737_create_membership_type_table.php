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
        Schema::dropIfExists('membership_types');

        Schema::create('membership_types', function (Blueprint $table) {
            $table->id('MembershipTypeID'); // Autoincrement som primær nøgle
            $table->string('TypeName')->unique(); // Unikhed for at undgå dubletter
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_type');
    }
};
