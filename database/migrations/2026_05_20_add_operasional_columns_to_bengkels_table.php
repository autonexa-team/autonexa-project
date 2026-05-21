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
        // Cek apakah column sudah ada
        if (!Schema::hasColumn('bengkels', 'jam_buka')) {
            Schema::table('bengkels', function (Blueprint $table) {
                $table->time('jam_buka')->nullable()->default('08:00')->after('telepon');
            });
        }

        if (!Schema::hasColumn('bengkels', 'jam_tutup')) {
            Schema::table('bengkels', function (Blueprint $table) {
                $table->time('jam_tutup')->nullable()->default('17:00')->after('jam_buka');
            });
        }

        if (!Schema::hasColumn('bengkels', 'hari_operasional')) {
            Schema::table('bengkels', function (Blueprint $table) {
                $table->json('hari_operasional')->nullable()->after('jam_tutup');
            });
        }

        if (!Schema::hasColumn('bengkels', 'kuota_slot')) {
            Schema::table('bengkels', function (Blueprint $table) {
                $table->json('kuota_slot')->nullable()->after('hari_operasional');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bengkels', function (Blueprint $table) {
            if (Schema::hasColumn('bengkels', 'jam_buka')) {
                $table->dropColumn('jam_buka');
            }
            if (Schema::hasColumn('bengkels', 'jam_tutup')) {
                $table->dropColumn('jam_tutup');
            }
            if (Schema::hasColumn('bengkels', 'hari_operasional')) {
                $table->dropColumn('hari_operasional');
            }
            if (Schema::hasColumn('bengkels', 'kuota_slot')) {
                $table->dropColumn('kuota_slot');
            }
        });
    }
};
