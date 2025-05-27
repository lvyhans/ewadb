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
        Schema::table('users', function (Blueprint $table) {
            // Company Information
            $table->string('company_name')->nullable();
            $table->string('company_registration_number')->nullable();
            $table->string('gstin')->nullable(); // GST Identification Number
            $table->string('pan_number')->nullable(); // Permanent Account Number
            $table->text('company_address')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_state')->nullable();
            $table->string('company_pincode')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            
            // Company Documents (store file paths)
            $table->string('company_registration_certificate')->nullable();
            $table->string('gst_certificate')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('address_proof')->nullable();
            $table->string('bank_statement')->nullable();
            
            // Approval Status
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'company_name',
                'company_registration_number',
                'gstin',
                'pan_number',
                'company_address',
                'company_city',
                'company_state',
                'company_pincode',
                'company_phone',
                'company_email',
                'company_registration_certificate',
                'gst_certificate',
                'pan_card',
                'address_proof',
                'bank_statement',
                'approval_status',
                'rejection_reason',
                'approved_at',
                'approved_by'
            ]);
        });
    }
};
