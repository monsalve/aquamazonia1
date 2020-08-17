<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalidadSiembra extends Model
{
    protected $table = 'calidad_siembra';

    protected $fillable = [
        'id_calidad_parametros',
        'id_siembra',
        'id_contenedor'
        
    ];
}
