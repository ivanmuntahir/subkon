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
            $table->integer('pic_phone_number')->nullable();
            $table->string('kota_proyek')->nullable();
            $table->string('provinsi_proyek')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('pic_phone_number');
            $table->dropColumn('kota_proyek');
            $table->dropColumn('provinsi_proyek');
        });
    }
};
