<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Recurso;

class Libro extends Model
{
    protected $fillable = [ 'tipo_id', 'code', 'libro', 'estado' ];

    // MUCHOS A MUCHOS 
    public function recursos(){
        return $this->belongsToMany(Recurso::class)->withPivot('link');
    }
    
}
