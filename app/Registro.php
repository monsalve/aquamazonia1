<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
	protected $table = 'registros';

	protected $fillable = [
		'id_siembra', 
		'id_especie',
		'fecha_registro',       
		'tipo_registro',
		'peso_ganado',
		'mortalidad',
		'biomasa',
		'cantidad', 
		'estado'
	];
}
