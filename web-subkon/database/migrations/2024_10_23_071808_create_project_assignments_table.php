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
        // Schema::create('project_assignments', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
        //     $table->integer('total_needed')->default(0);
        //     $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
        //     $table->foreignId('subkon_id')->constrained('subkon')->onDelete('cascade');
        //     $table->json('certificates_skills');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_assignments');
    }
};
