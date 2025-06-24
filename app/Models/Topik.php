<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topik extends Model
{
    use HasFactory;

    protected $table = 'topik';
    protected $primaryKey = 'id_topik';

    protected $fillable = [
        'id_level',     // tambahkan ini
        'nama_topik',
    ];

    public $timestamps = true;

    // Relasi ke model Level
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }
}
