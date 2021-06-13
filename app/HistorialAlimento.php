<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialAlimento extends Model
{
  protected $table = 'historial_costos_alimentos';

    protected $fillable = [
      'id_alimento',
      'costo',
      'fecha_registro'
    ];
}
