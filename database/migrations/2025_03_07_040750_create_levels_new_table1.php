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
        Schema::create('level', function (Blueprint $table) {
            $table->id('id_level'); // Primary Key
            $table->unsignedBigInteger('id_mataPelajaran'); // Foreign Key
            $table->string('penjelasan_level');
            $table->timestamps();
        
            // Definisikan foreign key
            $table->foreign('id_mataPelajaran')
                  ->references('id_mataPelajaran')
                  ->on('matapelajaran')
                  ->onDelete('cascade');
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
