<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';
    protected $primaryKey = 'id_soal';

    protected $fillable = [
        'id_topik',
        'tipeSoal',
        'media',
        'pertanyaan',
        'audioPertanyaan',
        'opsiA',
        'opsiB',
        'opsiC',
        'opsiD',
        'pasanganA',
        'pasanganB',
        'pasanganC',
        'pasanganD',
        'jawabanBenar',
    ];

    public $timestamps = true;

    // Relasi ke topik
    public function topik()
    {
        return $this->belongsTo(Topik::class, 'id_topik', 'id_topik');
    }
}
