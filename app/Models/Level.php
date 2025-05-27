<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'level';  // jika nama tabel bukan plural (levels)
    protected $primaryKey = 'id_level';

    protected $fillable = [
        'penjelasan_level',
        // 'id_mataPelajaran', // sudah dihapus kolom ini, jadi jangan dipakai
    ];

    public $timestamps = true; // sesuai kolom created_at dan updated_at

    // Jika kamu sebelumnya pakai relasi dengan mataPelajaran,
    // hapus atau sesuaikan jika memang sudah tidak dipakai.
}
