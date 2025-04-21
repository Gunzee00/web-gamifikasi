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
        Schema::create('materis', function (Blueprint $table) {
            $table->id('id_materi');
            
            // Foreign keys dengan onDelete('cascade')
            $table->foreignId('id_subjek')
                  ->constrained('subjeks', 'id_subjek')
                  ->onDelete('cascade');

            $table->foreignId('id_level')
                  ->constrained('levels', 'id_level')
                  ->onDelete('cascade');
            
            $table->string('nama_materi', 255);
            $table->string('media', 255)->nullable(); // Mengubah menjadi varchar (string)
            $table->string('judul', 255)->nullable();
            $table->text('soal')->nullable();
            $table->text('pilihan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
