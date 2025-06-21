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
            // Add admin_id to create admin-member hierarchy
            $table->unsignedBigInteger('admin_id')->nullable()->after('approved_by');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            
            // Add admin group identifier for better organization
            $table->string('admin_group_name')->nullable()->after('admin_id');
            
            // Add index for better query performance
            $table->index(['admin_id', 'approval_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropIndex(['admin_id', 'approval_status']);
            $table->dropColumn(['admin_id', 'admin_group_name']);
        });
    }
};
