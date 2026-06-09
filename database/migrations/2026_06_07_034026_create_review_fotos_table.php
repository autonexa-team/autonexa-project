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
        Schema::create('review_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')
                  ->constrained('reviews')
                  ->onDelete('cascade'); // jika review dihapus, foto ikut terhapus
            $table->string('foto');     // menyimpan path file, contoh: reviews/abc.jpg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_fotos');
    }
};
