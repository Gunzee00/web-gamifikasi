<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'matapelajaran';
    protected $primaryKey = 'id_mataPelajaran'; 

    protected $fillable = [
        'nama_mataPelajaran',
    ];
    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Relasi ke model Level.
     * Satu mata pelajaran bisa memiliki banyak level.
     */
    public function levels()
    {
        return $this->hasMany(Level::class, 'id_mataPelajaran', 'id_mataPelajaran');
    }

    public $timestamps = true;
}
