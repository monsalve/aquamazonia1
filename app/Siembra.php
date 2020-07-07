<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siembra extends Model
{
    //
    protected $table = 'siembras';

    protected $fillable = [
        'id_contenedor',
        'fecha_inicio',
        'estado',
        'fin_descanso'
      
    ];
}
