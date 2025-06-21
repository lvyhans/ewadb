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
        Schema::table('leads', function (Blueprint $table) {
            // Visa Information
            $table->string('visa_type')->nullable();
            $table->string('destination_country')->nullable();
            $table->date('preferred_travel_date')->nullable();
            $table->string('duration_of_stay')->nullable();
            $table->text('purpose_of_visit')->nullable();
            
            // Personal Information
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('passport_number')->nullable();
            
            // Additional fields
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'visa_type',
                'destination_country', 
                'preferred_travel_date',
                'duration_of_stay',
                'purpose_of_visit',
                'gender',
                'nationality',
                'marital_status',
                'passport_number',
                'notes'
            ]);
        });
    }
};
