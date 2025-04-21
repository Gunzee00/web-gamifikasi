<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('subjeks', function (Blueprint $table) {
            $table->id('id_subjek'); // Ini harus sama dengan foreign key di tabel materis
            $table->string('name_subjek')->unique();
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('subjeks');
    }
};
