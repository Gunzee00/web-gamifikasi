<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankPengguna extends Model
{
    use HasFactory;

    protected $table = 'rank_pengguna';
    protected $primaryKey = 'id_rank';

    protected $fillable = [
        'id_user',
        'total_bintang',
        'nama_rank',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
