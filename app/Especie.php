<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    protected $table = 'especies';

    protected $fillable = [
        'especie', 
        'descripcion'             
    ];
}
