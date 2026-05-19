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
        Schema::table('layanans', function (Blueprint $table) {
            // Drop kolom durasi yang lama (type time)
            $table->dropColumn('durasi');
        });
        
        Schema::table('layanans', function (Blueprint $table) {
            // Tambah kolom durasi baru sebagai integer (dalam menit)
            $table->integer('durasi')->default(30)->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn('durasi');
        });
        
        Schema::table('layanans', function (Blueprint $table) {
            $table->time('durasi')->nullable();
        });
    }
};
