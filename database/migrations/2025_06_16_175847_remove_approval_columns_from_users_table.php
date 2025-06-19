<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if we're using a database that supports dropping foreign keys by name
            if (DB::getDriverName() !== 'sqlite') {
                // Drop foreign key constraint first
                $table->dropForeign('users_approved_by_foreign');
            }
            
            // Then drop the columns
            $table->dropColumn([
                'approval_status',
                'rejection_reason',
                'approved_at',
                'approved_by'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }
};
