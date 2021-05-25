<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialRecurso extends Model
{
  protected $table = 'historial_costos_recursos';

  protected $fillable = [
    'id_recurso',
    'costo',
    'fecha_registro'
  ];
}
