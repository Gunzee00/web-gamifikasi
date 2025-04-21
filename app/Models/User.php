<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users'; // name tabel yang digunakan

    protected $primaryKey = 'id_user'; // Primary key tabel

    protected $fillable = [
        'role',
        'name',
        'username',
        'password',
        'gender',
        'tanggal_lahir'
    ];

    protected $hidden = [
        'password',
    ];

    public function rekapSkorPengguna()
{
    return $this->hasMany(RekapSkorPengguna::class, 'id_user', 'id_user');
}

}
