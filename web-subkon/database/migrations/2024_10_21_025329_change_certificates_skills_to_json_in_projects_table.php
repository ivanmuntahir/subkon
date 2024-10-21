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
            // Change the certificates_skills column to JSON type
            $table->json('certificates_skills')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // You may want to revert back to the original type, e.g., string
            $table->string('certificates_skills')->change(); 
        });
    }
};
