<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;
use App\RecursoNecesario;
use App\Recursos;
use App\Registro;
use App\Actividad;
use Illuminate\Support\Facades\DB;

class InfomeBiomasaAlimentoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    $siembras = Siembra::select(
      'siembras.id as id',
      'capacidad',
      'nombre_siembra',
      'id_contenedor',
      'fecha_inicio',
      'ini_descanso',
      'siembras.estado',
      'fin_descanso'
    )
      ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
      ->get();

    $especies = EspecieSiembra::select(
      'cant_actual',
      'contenedor',
      'capacidad',
      'especies_siembra.cantidad as cantidad_inicial',
      'especies_siembra.id_especie as id_especie',
      'especies_siembra.id_siembra as id_siembra',
      'fecha_inicio',
      'nombre_siembra',
      'peso_inicial',
      'peso_actual',
    )
      ->orderBy('especies_siembra.id_siembra')
      ->orderBy('especies_siembra.id_especie')
      ->join('siembras', 'especies_siembra.id_siembra', 'siembras.id')
      ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
      ->join('especies', 'especies_siembra.id_especie', 'especies.id')
      ->where('siembras.estado', '=', 1)
      ->get();

    $registros = Registro::select()
      ->join('siembras', 'registros.id_siembra', 'siembras.id')->where('siembras.estado', '=', '1')
      ->get();

    $recursos_necesarios = RecursoNecesario::select(
      'recursos_necesarios.id as id',
      'recursos_siembras.id_registro as id_registro',
      'id_siembra',
      'id_alimento',
      'id_recurso',
      'cantidad_recurso',
      'cant_manana',
      'cant_tarde',
      'conv_alimenticia',
      'minutos_hombre',
      'horas_hombre',
      'costo_kg as costo_alimento',
      'costo as costo_recurso'
    )
      ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
      ->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
      ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
      ->get();

    $mh = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

    $aux_regs = array();
    $diff = 0;
    $especies_siembra = new EspeciesSiembraController;

    if (count($siembras) > 0) {
      for ($i = 0; $i < count($siembras); $i++) {
        // Especies en la siembra

        if (count($especies) > 0) {

          $contador_esp = 0;
          foreach ($especies as $especie) {
            $especie->mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
            $especie->salida_animales = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad + $especie->mortalidad;
            $especie->cantidad_actual = $especie->cantidad_inicial - $especie->salida_animales;
            $especie->biomasa_disponible = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);
            $especie->biomasa_inicial =  ((($especie->peso_inicial) * ($especie->cantidad_inicial)) / 1000);
           

            if ($siembras[$i]->id == $especie->id_siembra) {
              $contador_esp++;
              $especie->cant_actual_especie += $especie->cant_actual;
              $siembras[$i]->cantidad_inicial += $especie->cantidad_inicial;
              $siembras[$i]->peso_ini += $especie->peso_inicial;
              $siembras[$i]->cant_actual += $especie->cant_actual;
              $siembras[$i]->peso_actual += $especie->peso_actual;
              $siembras[$i]->biomasa_inicial += $especie->biomasa_inicial;
              $siembras[$i]->biomasa_disponible += $especie->biomasa_disponible;
              $siembras[$i]->densidad_final = (number_format(($siembras[$i]->cant_actual / $especie->capacidad), 2, ',', ''));
              $siembras[$i]->carga_final = (number_format(($siembras[$i]->biomasa_disponible / $especie->capacidad), 2, ',', ''));

              for ($k = 0; $k < count($registros); $k++) {

                if ($especie->id_siembra == $registros[$k]->id_siembra) {

                  $int_tiempo = Registro::select('fecha_registro')
                    ->orderBy('fecha_registro', 'desc')
                    ->where('id_siembra', $especie->id_siembra)
                    ->where('id_especie', $especie->id_especie)
                    ->first();

                  $date1 = new \DateTime($especie->fecha_inicio);
                  if (isset($int_tiempo['fecha_registro'])) {
                    $date2 = new \DateTime($int_tiempo['fecha_registro']);
                  } else {
                    $date2 = new \DateTime();
                  }
                  $diff = $date1->diff($date2);
                  $especie->intervalo_tiempo  = $diff->days;

                  $especie->salida_biomasa = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->biomasa;

                  // $siembras[$i]->mortalidad = $especie->mortalidad;
                  if ($especie->id_especie == $registros[$k]->id_especie) {
                    $registros[$k]->mortalidad_kg = (($registros[$k]->mortalidad * $registros[$k]->peso_ganado) / 1000);

                    $especie->mortalidad += $registros[$k]->mortalidad;
                    // $especie->mortalidad_kg =  (($especie->mortalidad * $especie->peso_ganado)/1000);
                    $especie->mortalidad_kg += $registros[$k]->mortalidad_kg;
                    $especie->salida_biomasa_especie += $registros[$k]->biomasa;
                    $especie->salida_animales =  $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->cantidad;
                    $especie->peso_incremento = $especie->peso_actual -  $especie->peso_inicial;
                    $especie->incremento_biomasa = (($especie->peso_incremento * $especie->cant_actual) / 1000);
                    $especie->ganancia_peso_dia = $especie->peso_incremento;
                    if ($especie->intervalo_tiempo > 0) {
                      $especie->ganancia_peso_dia = $especie->peso_incremento / $especie->intervalo_tiempo;
                    }
                    $especie->mortalidad_porcentaje =  (($especie->mortalidad * 100) / $especie->cantidad_inicial);
                  }
                }
              }
              $siembras[$i]->mortalidad += $especie->mortalidad;
              $siembras[$i]->mortalidad_kg += $especie->mortalidad_kg;
              $siembras[$i]->mortalidad_porcentaje = (($siembras[$i]->mortalidad * 100) / $siembras[$i]->cantidad_inicial);
              $siembras[$i]->salida_biomasa = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->biomasa;;
              $siembras[$i]->salida_animales = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->cantidad;
              $siembras[$i]->incremento_biomasa += $especie->incremento_biomasa;
              $siembras[$i]->intervalo_tiempo = $especie->intervalo_tiempo;
              $siembras[$i]->porc_supervivencia_final = (($siembras[$i]->salida_animales * 100) / $siembras[$i]->cantidad_inicial);
            }
          }
        }
        for ($l = 0; $l < count($recursos_necesarios); $l++) {
          if ($siembras[$i]->id == $recursos_necesarios[$l]->id_siembra) {
            $siembras[$i]->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
            $siembras[$i]->horas_hombre += $recursos_necesarios[$l]->horas_hombre;
            $recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
            $siembras[$i]->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
            $recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
            $siembras[$i]->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
            $recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana) * $recursos_necesarios[$l]->costo_alimento;
            $siembras[$i]->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;

            if ($recursos_necesarios[$l]->conv_alimenticia > 0) {
              $recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
              $recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia, 2, ',', '');
              $siembras[$i]->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
            }
          }
        }
        $siembras[$i]->costo_minutos_hombre += ($siembras[$i]->minutos_hombre * $mh->costo);
        $siembras[$i]->costo_total_siembra = ($siembras[$i]->costo_minutos_hombre + $siembras[$i]->costo_total_alimento + $siembras[$i]->costo_total_recurso);

        if (($siembras[$i]->salida_biomasa) > 0) {
          $siembras[$i]->costo_produccion = $siembras[$i]->costo_total_siembra / $siembras[$i]->salida_biomasa;
        } else {
          $siembras[$i]->costo_produccion = 0;
        }

        if ($siembras[$i]->incr_bio_acum_conver > 0) {
          $siembras[$i]->conversion_alimenticia = ($siembras[$i]->cantidad_total_alimento) / ($siembras[$i]->incr_bio_acum_conver);
        }

        if ($siembras[$i]->incremento_biomasa > 0) {
          $siembras[$i]->conversion_alimenticia_siembra = $siembras[$i]->cantidad_total_alimento /  $siembras[$i]->incremento_biomasa;
        }

        if (($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial) > 0) {
          $siembras[$i]->conversion_alimenticia_parcial = $siembras[$i]->cantidad_total_alimento / ($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial);
        } else {
          $siembras[$i]->conversion_alimenticia_parcial = 0;
        }

        $siembras[$i]->bio_dispo_conver = ($siembras[$i]->biomasa_inicial + $siembras[$i]->incr_bio_acum_conver) - ($siembras[$i]->biomasa_disponible + $siembras[$i]->mortalidad_kg);
        $siembras[$i]->bio_dispo_alimen = (($siembras[$i]->incr_bio_acum_conver + $siembras[$i]->biomasa_inicial) - ($siembras[$i]->salida_biomasa + $siembras[$i]->mortalidad_kg));

        if (($siembras[$i]->bio_dispo_alimen) > 0) {
          $siembras[$i]->costo_produccion_parcial = $siembras[$i]->costo_total_siembra / $siembras[$i]->bio_dispo_alimen;
        } else {
          $siembras[$i]->costo_produccion_parcial = 0;
        }

        if ($siembras[$i]->salida_animales > 0 && $siembras[$i]->intervalo_tiempo > 0) {
          $siembras[$i]->ganancia_peso_dia = ((($siembras[$i]->salida_biomasa * 1000) / $siembras[$i]->salida_animales) / $siembras[$i]->intervalo_tiempo);
        }

        $siembras[$i]->contador_esp = $contador_esp;

        if (($siembras[$i]->contador_esp) > 0) {
          $siembras[$i]->peso_inicial = $siembras[$i]->peso_ini / $siembras[$i]->contador_esp;
        } else {
          $siembras[$i]->peso_inicial = 0;
        }

        if (($siembras[$i]->contador_esp) > 0) {
          $siembras[$i]->peso_actual_esp = $siembras[$i]->peso_actual / $siembras[$i]->contador_esp;
        } else {
          $siembras[$i]->peso_actual_esp = 0;
        }

        $siembras[$i]->conversion_alimenticia_siembra = number_format($siembras[$i]->conversion_alimenticia_siembra, 2, ',', '');
        $siembras[$i]->biomasa_disponible = number_format($siembras[$i]->biomasa_disponible, 2, ',', '');
        $siembras[$i]->mortalidad_kg = number_format($siembras[$i]->mortalidad_kg, 2, ',', '');
        $siembras[$i]->salida_animales = number_format($siembras[$i]->salida_animales, 2, ',', '');
        $siembras[$i]->incremento_biomasa = number_format($siembras[$i]->incremento_biomasa, 2, ',', '');
        $siembras[$i]->bio_dispo_conver = number_format($siembras[$i]->bio_dispo_conver, 2, ',', '');
        $siembras[$i]->bio_dispo_alimen = number_format($siembras[$i]->bio_dispo_alimen, 2, ',', '');
        $siembras[$i]->incr_bio_acum_conver = number_format($siembras[$i]->incr_bio_acum_conver, 2, ',', '');
        $siembras[$i]->ganancia_peso_dia = number_format($siembras[$i]->ganancia_peso_dia, 2, ',', '');
        $siembras[$i]->peso_inicial = number_format($siembras[$i]->peso_inicial, 2, ',', '');
        $siembras[$i]->mortalidad_porcentaje = number_format($siembras[$i]->mortalidad_porcentaje, 2, ',', '');
        $siembras[$i]->peso_actual_esp = number_format($siembras[$i]->peso_actual_esp, 2, ',', '');
        $siembras[$i]->horas_hombre = number_format($siembras[$i]->horas_hombre, 2, ',', '');
        $siembras[$i]->conversion_alimenticia = number_format($siembras[$i]->conversion_alimenticia, 2, ',', '');
        $siembras[$i]->conversion_alimenticia_parcial = number_format($siembras[$i]->conversion_alimenticia_parcial, 2, ',', '');
        $siembras[$i]->costo_produccion_parcial = number_format($siembras[$i]->costo_produccion_parcial, 2, ',', '');
        $siembras[$i]->costo_produccion = number_format($siembras[$i]->costo_produccion, 2, ',', '');
        $siembras[$i]->porc_supervivencia_final = number_format($siembras[$i]->porc_supervivencia_final, 2, ',', '');
        $siembras[$i]->costo_total_recurso = number_format($siembras[$i]->costo_total_recurso, 2, ',', '');
        $siembras[$i]->costo_total_alimento = number_format($siembras[$i]->costo_total_alimento, 2, ',', '');
        $siembras[$i]->costo_total_siembra = number_format($siembras[$i]->costo_total_siembra, 2, ',', '');
        // recursos_necesarios
        $aux_regs[] = [
          "biomasa_inicial" => $siembras[$i]->biomasa_inicial,
          "biomasa_disponible" => $siembras[$i]->biomasa_disponible,
          'bio_dispo_conver' => $siembras[$i]->bio_dispo_conver,
          'bio_dispo_alimen' => $siembras[$i]->bio_dispo_alimen,
          "carga_final" => $siembras[$i]->carga_final,
          "cantidad_inicial" => $siembras[$i]->cantidad_inicial,
          "cant_actual" => $siembras[$i]->cant_actual,
          "costo_minutosh" => $siembras[$i]->costo_minutos_hombre,
          "costo_total_recurso" => $siembras[$i]->costo_total_recurso,
          'cantidad_total_alimento' => $siembras[$i]->cantidad_total_alimento,
          "costo_total_alimento" => $siembras[$i]->costo_total_alimento,
          "costo_tot" => $siembras[$i]->costo_total_siembra,
          "costo_produccion" => $siembras[$i]->costo_produccion,
          "costo_produccion_parcial" => $siembras[$i]->costo_produccion_parcial,
          'conversion_alimenticia' => $siembras[$i]->conversion_alimenticia,
          'conversion_alimenticia_siembra' => $siembras[$i]->conversion_alimenticia_siembra,
          'conversion_alimenticia_parcial' => $siembras[$i]->conversion_alimenticia_parcial,
          "densidad_final" => $siembras[$i]->densidad_final,
          'ganancia_peso_dia' => $siembras[$i]->ganancia_peso_dia,
          "fecha_inicio" => $siembras[$i]->fecha_inicio,
          "horas_hombre" => $siembras[$i]->horas_hombre,
          "minutos_hombre" => $siembras[$i]->minutos_hombre,
          'incr_bio_acum_conver' => $siembras[$i]->incr_bio_acum_conver,
          'incremento_biomasa' => $siembras[$i]->incremento_biomasa,
          'intervalo_tiempo' => $siembras[$i]->intervalo_tiempo,
          "mortalidad" => $siembras[$i]->mortalidad,
          "mortalidad_kg" => $siembras[$i]->mortalidad_kg,
          "mortalidad_porcentaje" => $siembras[$i]->mortalidad_porcentaje,
          "nombre_siembra" => $siembras[$i]->nombre_siembra,
          "peso_inicial" => $siembras[$i]->peso_inicial,
          "peso_actual" => $siembras[$i]->peso_actual_esp,
          "salida_animales" => $siembras[$i]->salida_animales,
          "salida_biomasa" => $siembras[$i]->salida_biomasa,
          "porc_supervivencia_final" => $siembras[$i]->porc_supervivencia_final,
          'capacidad' => $siembras[$i]->capacidad

        ];
      }
    }


    return ['existencias' => $aux_regs];
  }

  public function filtroBiomasaAlimento(Request $request)
  {

    $c1 = "siembras.id";
    $op1 = '!=';
    $c2 = '-1';
    $c5 = "siembras.id";
    $op3 = '!=';
    $c6 = '-1';
    $c7 = "siembras.id";
    $op4 = '!=';
    $c8 = '-1';

    if ($request['f_siembra'] != '-1') {
      $c1 = "siembras.id";
      $op1 = '=';
      $c2 = $request['f_siembra'];
    }
    if ($request['f_inicio_d'] != '-1') {
      $c5 = "fecha_inicio";
      $op3 = '>=';
      $c6 = $request['f_inicio_d'];
    }
    if ($request['f_inicio_h'] != '-1') {
      $c7 = "fecha_inicio";
      $op4 = '<=';
      $c8 = $request['f_inicio_h'];
    }

    $siembras = Siembra::select(
      'siembras.id as id',
      'capacidad',
      'nombre_siembra',
      'id_contenedor',
      'fecha_inicio',
      'ini_descanso',
      'siembras.estado',
      'fin_descanso'
    )
      ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
      ->where($c1, $op1, $c2)
      ->where('siembras.estado', '=', 1)
      ->where($c5, $op3, $c6)
      ->where($c7, $op4, $c8)
      ->get();

    $especies = EspecieSiembra::select(
      'cant_actual',
      'contenedor',
      'capacidad',
      'especies_siembra.cantidad as cantidad_inicial',
      // 'especie',
      'especies_siembra.id_especie as id_especie',
      'especies_siembra.id_siembra as id_siembra',
      'fecha_inicio',
      'nombre_siembra',
      'peso_inicial',
      'peso_actual',
    )
      ->orderBy('especies_siembra.id_siembra')
      ->orderBy('especies_siembra.id_especie')
      ->join('siembras', 'especies_siembra.id_siembra', 'siembras.id')
      ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
      ->join('especies', 'especies_siembra.id_especie', 'especies.id')
      ->where('siembras.estado', '=', 1)
      ->get();
    $registros = Registro::select()
      ->join('siembras', 'registros.id_siembra', 'siembras.id')->where('siembras.estado', '=', '1')
      ->get();

    $recursos_necesarios = RecursoNecesario::select(
      'recursos_necesarios.id as id',
      'recursos_siembras.id_registro as id_registro',
      'id_siembra',
      'id_alimento',
      'id_recurso',
      'cantidad_recurso',
      'cant_manana',
      'cant_tarde',
      'conv_alimenticia',
      'minutos_hombre',
      'horas_hombre',
      'costo_kg as costo_alimento',
      'costo as costo_recurso'
    )
      ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
      ->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
      ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
      ->get();

    $mh = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();;

    $aux_regs = array();
    $diff = 0;
    $especies_siembra = new EspeciesSiembraController;
    if (count($siembras) > 0) {
      for ($i = 0; $i < count($siembras); $i++) {
        // Especies en la siembra
        if (count($especies) > 0) {
          $contador_esp = 0;
        	foreach ($especies as $especie) {

            $especie->mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
            $especie->biomasa = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->biomasa;
            $especie->salida_animales = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad + $especie->mortalidad;
            $especie->cantidad_actual = $especie->cantidad_inicial - $especie->salida_animales;
            $especie->biomasa_disponible = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);
            $especie->biomasa_inicial =  ((($especie->peso_inicial) * ($especie->cantidad_inicial)) / 1000);
            $bio_dispo = ((($especie->peso_actual) * ($especie->cant_actual)) / 1000);

            if ($siembras[$i]->id == $especie->id_siembra) {
              $contador_esp++;
              $especie->cant_actual_especie += $especie->cant_actual;
              $siembras[$i]->cantidad_inicial += $especie->cantidad_inicial;
              $siembras[$i]->peso_ini += $especie->peso_inicial;
              $siembras[$i]->cant_actual += $especie->cant_actual;
              $siembras[$i]->peso_actual += $especie->peso_actual;
              $siembras[$i]->biomasa_inicial += $especie->biomasa_inicial;
              $siembras[$i]->biomasa_disponible += $especie->biomasa_disponible;
              $siembras[$i]->densidad_final = (number_format(($siembras[$i]->cant_actual / $especie->capacidad), 2, ',', ''));
              $siembras[$i]->carga_final = (number_format(($siembras[$i]->biomasa_disponible / $especie->capacidad), 2, ',', ''));

              for ($k = 0; $k < count($registros); $k++) {
                if ($especie->id_siembra == $registros[$k]->id_siembra) {

                  $int_tiempo = Registro::select('fecha_registro')
                    ->orderBy('fecha_registro', 'desc')
                    ->where('id_siembra', $especie->id_siembra)
                    ->where('id_especie', $especie->id_especie)
                    ->first();
                  $date1 = new \DateTime($especie->fecha_inicio);
                  if (isset($int_tiempo['fecha_registro'])) {
                    $date2 = new \DateTime($int_tiempo['fecha_registro']);
                  } else {
                    $date2 = new \DateTime();
                  }
                  $diff = $date1->diff($date2);
                  $especie->intervalo_tiempo  = $diff->days;

                  $especie->salida_biomasa = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->biomasa;
                  if ($especie->id_especie == $registros[$k]->id_especie) {
                    $registros[$k]->mortalidad_kg = (($registros[$k]->mortalidad * $registros[$k]->peso_ganado) / 1000);
                    $especie->mortalidad += $registros[$k]->mortalidad;
                    $especie->mortalidad_kg += $registros[$k]->mortalidad_kg;
                    $especie->salida_biomasa_especie += $registros[$k]->biomasa;
                    $especie->salida_animales = (($especie->salida_biomasa_especie * 1000) / $especie->peso_actual);
                    $especie->peso_incremento = $especie->peso_actual -  $especie->peso_inicial;
                    $especie->incremento_biomasa = (($especie->peso_incremento * $especie->cant_actual) / 1000);
                  if($especie->ganancia_peso_dia > 0) {
                    $especie->ganancia_peso_dia = $especie->peso_incremento / $especie->intervalo_tiempo;
                  }
                    $especie->mortalidad_porcentaje =  (($especie->mortalidad * 100) / $especie->cantidad_inicial);
                  }
                }
              }
              $siembras[$i]->mortalidad += $especie->mortalidad;
              $siembras[$i]->mortalidad_kg += $especie->mortalidad_kg;
              $siembras[$i]->mortalidad_porcentaje = (($siembras[$i]->mortalidad * 100) / $siembras[$i]->cantidad_inicial);
              $siembras[$i]->salida_biomasa = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->biomasa;;
              $siembras[$i]->salida_animales = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->cantidad;
              $siembras[$i]->incremento_biomasa += $especie->incremento_biomasa;
              $siembras[$i]->intervalo_tiempo = $especie->intervalo_tiempo;
              $siembras[$i]->porc_supervivencia_final = (($siembras[$i]->salida_animales * 100) / $siembras[$i]->cantidad_inicial);
            }
          }
        }
        for ($l = 0; $l < count($recursos_necesarios); $l++) {
          if ($siembras[$i]->id == $recursos_necesarios[$l]->id_siembra) {
            $siembras[$i]->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
            $siembras[$i]->horas_hombre += $recursos_necesarios[$l]->horas_hombre;
            $recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
            $siembras[$i]->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
            $recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
            $siembras[$i]->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
            $recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana) * $recursos_necesarios[$l]->costo_alimento;
            $siembras[$i]->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;

            if ($recursos_necesarios[$l]->conv_alimenticia > 0) {
              $recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
              $recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia, 2, ',', '');
              $siembras[$i]->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
            }
          }
        }
        $siembras[$i]->costo_minutos_hombre += ($siembras[$i]->minutos_hombre * $mh->costo);
        $siembras[$i]->costo_total_siembra = ($siembras[$i]->costo_minutos_hombre + $siembras[$i]->costo_total_alimento + $siembras[$i]->costo_total_recurso);
        if (($siembras[$i]->salida_biomasa) > 0) {
          $siembras[$i]->costo_produccion = $siembras[$i]->costo_total_siembra / $siembras[$i]->salida_biomasa;
        } else {
          $siembras[$i]->costo_produccion = 0;
        }

        if ($siembras[$i]->incr_bio_acum_conver > 0) {
          $siembras[$i]->conversion_alimenticia = ($siembras[$i]->cantidad_total_alimento) / ($siembras[$i]->incr_bio_acum_conver);
        }
        if ($siembras[$i]->incremento_biomasa > 0) {
          $siembras[$i]->conversion_alimenticia_siembra = $siembras[$i]->cantidad_total_alimento /  $siembras[$i]->incremento_biomasa;
        } else {
          $siembras[$i]->conversion_alimenticia_siembra = 0;
        }

        $siembras[$i]->bio_dispo_conver = ($siembras[$i]->biomasa_inicial + $siembras[$i]->incr_bio_acum_conver) - ($siembras[$i]->biomasa_disponible + $siembras[$i]->mortalidad_kg);
        $siembras[$i]->bio_dispo_alimen = (($siembras[$i]->incr_bio_acum_conver + $siembras[$i]->biomasa_inicial) - ($siembras[$i]->salida_biomasa + $siembras[$i]->mortalidad_kg));

        if (($siembras[$i]->bio_dispo_alimen) > 0) {
          $siembras[$i]->costo_produccion_parcial = $siembras[$i]->costo_total_siembra / $siembras[$i]->bio_dispo_alimen;
        } else {
          $siembras[$i]->costo_produccion_parcial = 0;
        }

        if (($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial) > 0) {
          $siembras[$i]->conversion_alimenticia_parcial = $siembras[$i]->cantidad_total_alimento / ($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial);
        } else {
          $siembras[$i]->conversion_alimenticia_parcial = 0;
        }
        $siembras[$i]->contador_esp = $contador_esp;
        $siembras[$i]->peso_inicial = $siembras[$i]->peso_ini / $siembras[$i]->contador_esp;
        $siembras[$i]->peso_actual_esp = $siembras[$i]->peso_actual / $siembras[$i]->contador_esp;
        $siembras[$i]->conversion_alimenticia_siembra = number_format($siembras[$i]->conversion_alimenticia_siembra, 2, ',', '');
        $siembras[$i]->biomasa_disponible = number_format($siembras[$i]->biomasa_disponible, 2, ',', '');
        $siembras[$i]->mortalidad_kg = number_format($siembras[$i]->mortalidad_kg, 2, ',', '');
        $siembras[$i]->salida_animales = number_format($siembras[$i]->salida_animales, 2, ',', '');
        $siembras[$i]->incremento_biomasa = number_format($siembras[$i]->incremento_biomasa, 2, ',', '');
        $siembras[$i]->bio_dispo_conver = number_format($siembras[$i]->bio_dispo_conver, 2, ',', '');
        $siembras[$i]->bio_dispo_alimen = number_format($siembras[$i]->bio_dispo_alimen, 2, ',', '');
        $siembras[$i]->incr_bio_acum_conver = number_format($siembras[$i]->incr_bio_acum_conver, 2, ',', '');
        $siembras[$i]->ganancia_peso_dia = number_format($siembras[$i]->ganancia_peso_dia, 2, ',', '');
        $siembras[$i]->peso_inicial = number_format($siembras[$i]->peso_inicial, 2, ',', '');
        $siembras[$i]->mortalidad_porcentaje = number_format($siembras[$i]->mortalidad_porcentaje, 2, ',', '');
        $siembras[$i]->peso_actual_esp = number_format($siembras[$i]->peso_actual_esp, 2, ',', '');
        $siembras[$i]->horas_hombre = number_format($siembras[$i]->horas_hombre, 2, ',', '');
        $siembras[$i]->conversion_alimenticia_parcial = number_format($siembras[$i]->conversion_alimenticia_parcial, 2, ',', '');
        $siembras[$i]->conversion_alimenticia = number_format($siembras[$i]->conversion_alimenticia, 2, ',', '');
        $siembras[$i]->costo_produccion = number_format($siembras[$i]->costo_produccion, 2, ',', '');
        $siembras[$i]->costo_produccion_parcial = number_format($siembras[$i]->costo_produccion_parcial, 2, ',', '');
        $siembras[$i]->porc_supervivencia_final = number_format($siembras[$i]->porc_supervivencia_final, 2, ',', '');
        $siembras[$i]->costo_total_recurso = number_format($siembras[$i]->costo_total_recurso, 2, ',', '');
        $siembras[$i]->costo_total_alimento = number_format($siembras[$i]->costo_total_alimento, 2, ',', '');
        $siembras[$i]->costo_total_siembra = number_format($siembras[$i]->costo_total_siembra, 2, ',', '');
        // recursos_necesarios
        $aux_regs[] = [
          "biomasa_inicial" => $siembras[$i]->biomasa_inicial,
          "biomasa_disponible" => $siembras[$i]->biomasa_disponible,
          'bio_dispo_conver' => $siembras[$i]->bio_dispo_conver,
          'bio_dispo_alimen' => $siembras[$i]->bio_dispo_alimen,
          "carga_final" => $siembras[$i]->carga_final,
          'capacidad' => $siembras[$i]->capacidad,
          "cantidad_inicial" => $siembras[$i]->cantidad_inicial,
          "cant_actual" => $siembras[$i]->cant_actual,
          "costo_minutosh" => $siembras[$i]->costo_minutos_hombre,
          "costo_total_recurso" => $siembras[$i]->costo_total_recurso,
          'cantidad_total_alimento' => $siembras[$i]->cantidad_total_alimento,
          "costo_total_alimento" => $siembras[$i]->costo_total_alimento,
          "costo_tot" => $siembras[$i]->costo_total_siembra,
          "costo_produccion" => $siembras[$i]->costo_produccion,
          "costo_produccion_parcial" => $siembras[$i]->costo_produccion_parcial,
          'conversion_alimenticia' => $siembras[$i]->conversion_alimenticia,
          'conversion_alimenticia_parcial' => $siembras[$i]->conversion_alimenticia_parcial,
          'conversion_alimenticia_siembra' => $siembras[$i]->conversion_alimenticia_siembra,
          "densidad_final" => $siembras[$i]->densidad_final,
          'ganancia_peso_dia' => $siembras[$i]->ganancia_peso_dia,
          "fecha_inicio" => $siembras[$i]->fecha_inicio,
          "horas_hombre" => $siembras[$i]->horas_hombre,
          "minutos_hombre" => $siembras[$i]->minutos_hombre,
          'incr_bio_acum_conver' => $siembras[$i]->incr_bio_acum_conver,
          'incremento_biomasa' => $siembras[$i]->incremento_biomasa,
          'intervalo_tiempo' => $siembras[$i]->intervalo_tiempo,
          "mortalidad" => $siembras[$i]->mortalidad,
          "mortalidad_kg" => $siembras[$i]->mortalidad_kg,
          "mortalidad_porcentaje" => $siembras[$i]->mortalidad_porcentaje,
          "nombre_siembra" => $siembras[$i]->nombre_siembra,
          "peso_inicial" => $siembras[$i]->peso_inicial,
          "peso_actual" => $siembras[$i]->peso_actual_esp,
          "salida_animales" => $siembras[$i]->salida_animales,
          "salida_biomasa" => $siembras[$i]->salida_biomasa,
          "porc_supervivencia_final" => $siembras[$i]->porc_supervivencia_final,
        ];
      }
    }
    return ['existencias' => $aux_regs];
  }
}
