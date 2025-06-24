<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkorPengguna extends Model
{
    use HasFactory;

    protected $table = 'skor_pengguna';

    protected $primaryKey = 'id_skor';

    protected $fillable = [
        'id_user',
        'id_topik',         // ganti dari id_level
        'jumlah_benar',
        'nama_topik',       // ganti dari nama_level
        'jumlah_bintang',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Topik
    public function topik()
    {
        return $this->belongsTo(Topik::class, 'id_topik', 'id_topik');
    }
}
