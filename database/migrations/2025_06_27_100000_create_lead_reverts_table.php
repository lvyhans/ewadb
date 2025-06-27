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
        Schema::create('lead_reverts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->string('ref_no'); // Lead reference number for external API access
            $table->text('revert_message'); // The revert/remark content
            $table->string('revert_type')->default('remark'); // Type: remark, revert, feedback, etc.
            $table->string('submitted_by')->nullable(); // External team/person identifier
            $table->string('team_name')->nullable(); // Name of the external team
            $table->string('priority')->default('normal'); // Priority: low, normal, high, urgent
            $table->string('status')->default('active'); // Status: active, resolved, archived
            $table->json('metadata')->nullable(); // Additional data from external team
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['lead_id', 'created_at']);
            $table->index(['ref_no', 'created_at']);
            $table->index(['status', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_reverts');
    }
};
