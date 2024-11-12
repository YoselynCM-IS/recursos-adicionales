<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libro;
use App\User;
use App\Role;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id', 'libro_id', 'codigo', 'months', 'limite'
    ];

    // 1 a MUCHOS
    // Un codigo puede tener muchos usuarios
    public function users(){
        return $this->hasMany(User::class);
    }

    // 1 a muchos (INVERSO)
    //Un codigo solo puede pertenecer a un codigo
    public function libro(){
        return $this->belongsTo(Libro::class);
    }

    // 1 a muchos (INVERSO)
    //Un codigo solo puede pertenecer a un rol
    public function role(){
        return $this->belongsTo(Role::class);
    }

}
