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
        'id_level',
        'jumlah_benar',
        'nama_level',
        'jumlah_bintang',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Level
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }
}
