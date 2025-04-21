<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapSkorPengguna extends Model
{
    use HasFactory;

    protected $table = 'rekap_skor_pengguna';
    protected $primaryKey = 'id_rekap';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
      'id_mataPelajaran',
      'id_level',
        'total_visual',
        'total_auditori',
        'total_kinestetik',
 
    ];

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
