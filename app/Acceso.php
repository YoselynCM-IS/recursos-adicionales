<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Libro;
use App\User;

class Acceso extends Model
{
    protected $fillable = [
        'user_id', 'libro_id', 'months', 'inicio', 'final'
    ];

    // 1 a 1 (INVERSO)
    //Un acceso solo puede tener un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 1 a 1 (Inverso)
    // Un acceso solo puede tener un libro
    public function libro(){
        return $this->belongsTo(Libro::class);
    }
}
