<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo'
    ];

    // 1 a MUCHOS
    // Un codigo puede tener muchos usuarios
    public function users(){
        return $this->hasMany(User::class);
    }

}
