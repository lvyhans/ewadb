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
        Schema::table('applications', function (Blueprint $table) {
            // TOEFL detailed scores (missing columns)
            $table->string('toefl_listening')->nullable()->after('toefl_score');
            $table->string('toefl_reading')->nullable()->after('toefl_listening');
            $table->string('toefl_writing')->nullable()->after('toefl_reading');
            $table->string('toefl_speaking')->nullable()->after('toefl_writing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'toefl_listening', 'toefl_reading', 'toefl_writing', 'toefl_speaking'
            ]);
        });
    }
};
