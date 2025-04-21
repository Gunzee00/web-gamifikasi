<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkorPengguna extends Model
{
    use HasFactory;

    protected $table = 'skor_pengguna';

    protected $primaryKey = 'id_skor';

    public $timestamps = false; // Tambahkan ini

    protected $fillable = ['id_user', 'id_mataPelajaran', 'id_level', 'tipeSoal', 'jumlah_benar', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mataPelajaran');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level');
    }
}
