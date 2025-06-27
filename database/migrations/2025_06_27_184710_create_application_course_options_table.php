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
        Schema::create('application_course_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->string('country');
            $table->string('city');
            $table->string('college');
            $table->string('course');
            $table->string('course_type')->nullable();
            $table->string('fees')->nullable();
            $table->string('duration')->nullable();
            $table->string('college_detail_id')->nullable(); // Reference to external course finder system
            $table->boolean('is_primary')->default(false); // Mark the primary course option
            $table->integer('priority_order')->default(1); // Order of preference
            $table->timestamps();
            
            $table->index(['application_id']);
            $table->index(['application_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_course_options');
    }
};
