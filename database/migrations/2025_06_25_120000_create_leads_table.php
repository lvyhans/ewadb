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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            
            // Reference and basic info
            $table->string('ref_no')->nullable();
            $table->string('name');
            $table->date('dob')->nullable();
            $table->string('father_name')->nullable();
            $table->string('phone');
            $table->string('alt_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            
            // Country and college preferences
            $table->string('preferred_country')->nullable();
            $table->string('preferred_city')->nullable();
            $table->string('preferred_college')->nullable();
            $table->string('preferred_course')->nullable();
            
            // Background information
            $table->text('travel_history')->nullable();
            $table->text('any_refusal')->nullable();
            $table->string('spouse_name')->nullable();
            $table->text('any_gap')->nullable();
            
            // English proficiency
            $table->enum('score_type', ['ielts', 'pte', 'duolingo'])->nullable();
            
            // IELTS scores
            $table->decimal('ielts_listening', 3, 1)->nullable();
            $table->decimal('ielts_reading', 3, 1)->nullable();
            $table->decimal('ielts_writing', 3, 1)->nullable();
            $table->decimal('ielts_speaking', 3, 1)->nullable();
            $table->decimal('ielts_overall', 3, 1)->nullable();
            
            // PTE scores
            $table->integer('pte_listening')->nullable();
            $table->integer('pte_reading')->nullable();
            $table->integer('pte_writing')->nullable();
            $table->integer('pte_speaking')->nullable();
            $table->integer('pte_overall')->nullable();
            
            // Duolingo scores
            $table->integer('duolingo_listening')->nullable();
            $table->integer('duolingo_reading')->nullable();
            $table->integer('duolingo_writing')->nullable();
            $table->integer('duolingo_speaking')->nullable();
            $table->integer('duolingo_overall')->nullable();
            
            // Qualifications
            $table->enum('last_qualification', ['12th', 'Diploma', 'Graduation', 'Post Graduation'])->nullable();
            
            // 12th details
            $table->string('twelfth_year')->nullable();
            $table->string('twelfth_percentage')->nullable();
            $table->string('twelfth_major')->nullable();
            
            // 10th details
            $table->string('tenth_year')->nullable();
            $table->string('tenth_percentage')->nullable();
            $table->string('tenth_major')->nullable();
            
            // Diploma details
            $table->string('diploma_year')->nullable();
            $table->string('diploma_percentage')->nullable();
            $table->string('diploma_major')->nullable();
            
            // Graduation details
            $table->string('graduation_year')->nullable();
            $table->string('graduation_percentage')->nullable();
            $table->string('graduation_major')->nullable();
            
            // Post graduation details
            $table->string('post_graduation_year')->nullable();
            $table->string('post_graduation_percentage')->nullable();
            $table->string('post_graduation_major')->nullable();
            
            // Previous visa application
            $table->text('previous_visa_application')->nullable();
            
            // Source and reference
            $table->string('source')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('visa_counselor')->nullable();
            
            // Remarks and status
            $table->text('remarks')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'rejected'])->default('new');
            
            // Lead assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
