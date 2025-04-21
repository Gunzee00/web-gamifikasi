<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi plural otomatis Laravel
    protected $table = 'level';

    protected $primaryKey = 'id_level'; // Ubah sesuai dengan kolom primary key


    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'id_mataPelajaran',
        'penjelasan_level',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Relasi ke model MataPelajaran.
     * Satu level terkait dengan satu mata pelajaran.
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mataPelajaran', 'id_mataPelajaran');
    }

    // Level.php
// public function mataPelajaran()
// {
//     return $this->belongsTo(MataPelajaran::class, 'id_mataPelajaran');
// }


    /**
     * Relasi ke tabel Soal (One to Many)
     */
    public function soal()
    {
        return $this->hasMany(Soal::class, 'id_level', 'id_level');
    }

    /**
     * Menambahkan otomatis kolom created_at dan updated_at.
     */
    public $timestamps = true;
}
