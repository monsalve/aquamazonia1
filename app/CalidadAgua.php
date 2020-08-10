<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalidadAgua extends Model
{
    protected $table = 'calidad_agua';

    protected $fillable = [
        'fecha_parametro',
        '12_am',
        '4_am',
        '7_am',
        '4_pm',
        '8_pm', 
        'temperatura',
        'ph',
        'amonio',
        'nitrito',
        'nitrato',
        'amonio', 
        'otros'
    ];
}
