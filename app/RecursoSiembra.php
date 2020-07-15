<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecursoSiembra extends Model{
  protected $table = 'recursos_siembras';

    protected $fillable = [
        'id_registro',
        'id_siembra'
    ];
}