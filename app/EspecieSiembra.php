<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EspecieSiembra extends Model
{
    //
    protected $table = 'especies_siembra';

    protected $fillable = [
        'id_siembra',
        'id_especie',
        'lote',
        'cantidad',
        'peso_inicial',
        'cant_actual',
        'peso_actual'
    ];
}
