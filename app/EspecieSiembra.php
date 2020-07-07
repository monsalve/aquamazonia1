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
        'cantidad',
        'peso_inicial'
    ];
}
