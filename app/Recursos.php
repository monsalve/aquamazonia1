<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recursos extends Model
{
    protected $table = 'recursos';

    protected $fillable = [
        
        'costo',
        'recurso',
        'unidad'
    ];
}
