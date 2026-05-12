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
        Schema::table('sparepart', function (Blueprint $table) {
            // Kolom sudah ditambahkan di create_sparepart_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sparepart', function (Blueprint $table) {
            // Tidak ada yang di-reverse
        });
    }
};
