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
        // Cek apakah column telepon sudah ada
        if (!Schema::hasColumn('bengkels', 'telepon')) {
            Schema::table('bengkels', function (Blueprint $table) {
                $table->string('telepon')->nullable()->after('kota');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bengkels', function (Blueprint $table) {
            if (Schema::hasColumn('bengkels', 'telepon')) {
                $table->dropColumn('telepon');
            }
        });
    }
};
