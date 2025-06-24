<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $table = 'level';
    protected $primaryKey = 'id_level';
    
    protected $fillable = ['nama_level']; // sesuaikan dengan struktur tabelmu

    public $timestamps = true;

    public function topiks()
    {
        return $this->hasMany(Topik::class, 'id_level', 'id_level');
    }
}
