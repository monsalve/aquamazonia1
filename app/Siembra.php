<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siembra extends Model
{
    //
    protected $table = 'siembras';

    protected $fillable = [
        'id_contenedor',
        'nombre_siembra',
        'fecha_inicio',
        'fecha_alimento',
        'estado',
        'ini_descanso',
        'fin_descanso'
    ];
}
