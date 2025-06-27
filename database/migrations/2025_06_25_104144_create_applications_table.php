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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->string('lead_ref_no')->nullable();
            
            // Personal Information
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            
            // Address Information
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Study Preferences
            $table->string('preferred_country')->nullable();
            $table->string('preferred_city')->nullable();
            $table->string('preferred_college')->nullable();
            $table->enum('course_level', ['certificate', 'diploma', 'bachelor', 'master', 'phd'])->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('intake_year')->nullable();
            $table->enum('intake_month', ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'])->nullable();
            
            // English Proficiency
            $table->enum('english_proficiency', ['ielts', 'toefl', 'pte', 'other', 'none'])->nullable();
            $table->string('ielts_score')->nullable();
            $table->string('toefl_score')->nullable();
            $table->string('pte_score')->nullable();
            $table->string('other_english_test')->nullable();
            
            // Educational Background
            $table->string('bachelor_degree')->nullable();
            $table->string('bachelor_university')->nullable();
            $table->string('bachelor_percentage')->nullable();
            $table->string('bachelor_year')->nullable();
            $table->string('master_degree')->nullable();
            $table->string('master_university')->nullable();
            $table->string('master_percentage')->nullable();
            $table->string('master_year')->nullable();
            $table->string('twelfth_board')->nullable();
            $table->string('twelfth_school')->nullable();
            $table->string('twelfth_percentage')->nullable();
            $table->string('twelfth_year')->nullable();
            $table->string('tenth_board')->nullable();
            $table->string('tenth_school')->nullable();
            $table->string('tenth_percentage')->nullable();
            $table->string('tenth_year')->nullable();
            
            // Additional Information
            $table->boolean('visa_refusal_history')->default(false);
            $table->text('refusal_details')->nullable();
            $table->boolean('travel_history')->default(false);
            $table->enum('financial_support', ['self', 'parents', 'sponsor', 'loan', 'scholarship'])->nullable();
            $table->string('sponsor_name')->nullable();
            $table->string('sponsor_relationship')->nullable();
            $table->decimal('estimated_budget', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            
            // System fields
            $table->enum('status', ['pending', 'in_progress', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->string('visa_counselor')->nullable();
            
            $table->timestamps();
            
            $table->index(['application_number']);
            $table->index(['lead_ref_no']);
            $table->index(['email']);
            $table->index(['status']);
            $table->index(['created_by']);
            $table->index(['assigned_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
