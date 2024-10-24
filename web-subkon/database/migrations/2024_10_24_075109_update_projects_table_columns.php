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
            // Drop the existing columns if they exist
            if (Schema::hasColumn('projects', 'provinsi_proyek')) {
                $table->dropColumn('provinsi_proyek');
            }

            if (Schema::hasColumn('projects', 'kota_proyek')) {
                $table->dropColumn('kota_proyek');
            }

            // Add new columns for province and regency IDs
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('regency_id')->constrained('regencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop the foreign key constraints first
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);

            // Drop the new columns
            $table->dropColumn(['province_id', 'regency_id']);

            // Re-add the old columns if needed (not included in this example)
            // $table->string('provinsi_proyek');
            // $table->string('kota_proyek');
        });

    }
};
