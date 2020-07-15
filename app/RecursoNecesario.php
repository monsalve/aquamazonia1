<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecursoNecesario extends Model{
  protected $table = 'recursos_necesarios';

    protected $fillable = [
        'id_recurso',
        'id_alimento',
        'tipo_actividad',
        'fecha_ra',
        'horas_hombre',
        'cant_manana',
        'cant_tarde',
        'detalles'
    ];
}