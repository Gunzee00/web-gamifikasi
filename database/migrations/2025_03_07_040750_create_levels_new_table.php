<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel levels.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id('id_level');
            $table->unsignedBigInteger('id_mataPelajaran');
            $table->string('penjelasan_level');
            $table->timestamps();

            // Menambahkan foreign key ke tabel mata_pelajarans
            $table->foreign('id_mataPelajaran')
                  ->references('id')
                  ->on('mata_pelajarans')
                  ->onDelete('cascade'); // Menghapus level jika mata pelajaran dihapus
        });
    }

    /**
     * Membatalkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('levels');
    }
};
