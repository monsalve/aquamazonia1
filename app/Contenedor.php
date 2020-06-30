<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contenedor extends Model
{
    protected $table = 'contenedores';

    protected $fillable = [
        'contenedor'
        , 'capacidad'
        , 'estado'        
    ];
}
