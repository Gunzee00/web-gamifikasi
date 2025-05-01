<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $table = 'level';
    protected $primaryKey = 'id_level';
    protected $fillable = [
        'id_mataPelajaran',
        'penjelasan_level',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mataPelajaran', 'id_mataPelajaran');
    }

    

  //Relasi ke tabel Soal (One to Many)
    public function soal()
    {
        return $this->hasMany(Soal::class, 'id_level', 'id_level');
    }

//Menambahkan otomatis kolom created_at dan updated_at.
    public $timestamps = true;
}
