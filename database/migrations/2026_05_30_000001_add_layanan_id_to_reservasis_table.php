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
        Schema::table('reservasis', function (Blueprint $table) {
            // Add layanan_id if it doesn't exist
            if (!Schema::hasColumn('reservasis', 'layanan_id')) {
                $table->foreignId('layanan_id')
                    ->nullable()
                    ->after('bengkel_id')
                    ->constrained('layanans')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            if (Schema::hasColumn('reservasis', 'layanan_id')) {
                $table->dropForeignKeyIfExists(['layanan_id']);
                $table->dropColumn('layanan_id');
            }
        });
    }
};
