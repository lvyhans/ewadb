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
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->string('document_name');
            $table->string('document_type')->nullable(); // Type from API (passport, degree, etc.)
            $table->boolean('is_mandatory')->default(false);
            $table->string('file_path')->nullable(); // Path to uploaded file
            $table->string('original_filename')->nullable();
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->enum('status', ['pending', 'uploaded', 'verified', 'rejected'])->default('pending');
            $table->text('comments')->nullable();
            $table->timestamps();
            
            $table->index(['application_id']);
            $table->index(['document_type']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
