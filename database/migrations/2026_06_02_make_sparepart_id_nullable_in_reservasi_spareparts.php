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
        Schema::table('reservasi_spareparts', function (Blueprint $table) {
            // Make sparepart_id nullable
            $table->foreignId('sparepart_id')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi_spareparts', function (Blueprint $table) {
            $table->foreignId('sparepart_id')
                ->nullable(false)
                ->change();
        });
    }
};
