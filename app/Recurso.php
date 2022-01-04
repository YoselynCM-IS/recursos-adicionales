<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Libro;
use App\Role;

class Recurso extends Model
{
    protected $fillable = [ 'role_id', 'tipo_id', 'recurso' ];

    // MUCHOS A MUCHOS 
    public function libros(){
        return $this->belongsToMany(Libro::class)->withPivot('link');
    }

    // 1 a 1 (INVERSO)
    //Un recurso solo puede tener un rol
    public function role(){
        return $this->belongsTo(Role::class);
    }
}
