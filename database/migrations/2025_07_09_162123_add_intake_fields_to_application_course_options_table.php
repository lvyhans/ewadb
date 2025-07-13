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
        Schema::table('application_course_options', function (Blueprint $table) {
            $table->year('intake_year')->nullable()->after('duration');
            $table->string('intake_month')->nullable()->after('intake_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_course_options', function (Blueprint $table) {
            $table->dropColumn(['intake_year', 'intake_month']);
        });
    }
};
