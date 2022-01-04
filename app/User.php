<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Acceso;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'codigo', 'name', 'email', 'password', 'estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 1 a 1 (INVERSO)
    //Un usuario solo puede tener un rol
    public function role(){
        return $this->belongsTo(Role::class);
    }

    // 1 a 1
    // Un usuario solo puede tener un acceso
    public function acceso(){
        return $this->hasOne(Acceso::class);
    }

    public static function navigation(){
        return auth()->check() ? auth()->user()->role->role : 'guest';
        //Con la función check Verifica si el usuario esta o no autentficado
        //Si si esta autentificado accede y mediante un objeto de la clase user (en este caso user()) accede al metodo rol() y extrae el nombre dle rol, de no ser asi sera invitado
    }
}
