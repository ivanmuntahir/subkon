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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subkon_id')->constrained('subkons')->onDelete('cascade');  // Link to subkon
            $table->string('name');  // Name of the project
            $table->string('pic_name');
            $table->integer('total_needed');
            $table->json('certificates_skills');  // Required certificates/skills for the project
            $table->longText('comment')->nullable();
            $table->string('attachment_bast');
            $table->string('attachment_photo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
