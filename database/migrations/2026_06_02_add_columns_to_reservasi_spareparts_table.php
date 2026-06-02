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
            // Add nama and keterangan columns if they don't exist
            if (!Schema::hasColumn('reservasi_spareparts', 'nama')) {
                $table->string('nama')->after('reservasi_id');
            }
            if (!Schema::hasColumn('reservasi_spareparts', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('harga');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi_spareparts', function (Blueprint $table) {
            $table->dropColumn(['nama', 'keterangan']);
        });
    }
};
