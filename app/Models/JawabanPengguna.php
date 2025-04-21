<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 

class JawabanPengguna extends Model
{
    use HasFactory;

    protected $table = 'jawaban_pengguna';

    protected $primaryKey = 'id_jawaban';

    public $timestamps = false; // Sesuaikan jika tabel tidak punya created_at & updated_at

    protected $fillable = ['id_user', 'id_soal', 'jawaban_siswa', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user'); 
        // id_user di JawabanPengguna -> id_user di Users
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'id_soal', 'id_soal');
    }
}
