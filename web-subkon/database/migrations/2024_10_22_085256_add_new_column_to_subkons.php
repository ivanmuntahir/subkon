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
        Schema::table('subkons', function (Blueprint $table) {
           $table->string('kota')->nullable();
           $table->string('provinsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subkons', function (Blueprint $table) {
           $table->dropColumn('kota');
           $table->dropColumn('provinsi');
        });
    }
};
