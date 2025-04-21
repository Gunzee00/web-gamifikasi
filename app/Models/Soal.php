<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';
    protected $primaryKey = 'id_soal'; // Ubah primary key sesuai dengan nama di tabel


    protected $fillable = [
        'id_level',
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

    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }

    public $timestamps = true;
}
