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
        Schema::table('projects', function (Blueprint $table) {
            $table->date('project_started')->nullable(); // Column for project start date
            $table->date('project_deadline')->nullable(); // Column for project deadline
            $table->integer('duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('project_started');
            $table->dropColumn('project_deadline');
            $table->dropColumn('duration');
        });
    }
};
