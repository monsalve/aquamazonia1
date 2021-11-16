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
      foreach ($siembras as $siembra) {
        // Especies en la siembra

        if (count($especies) > 0) {

          $contador_esp = 0;
          foreach ($especies as $especie) {
            $especie->mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
            $especie->salida_animales = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad + $especie->mortalidad;
            $especie->cantidad_actual = $especie->cantidad_inicial - $especie->salida_animales;
            $especie->biomasa_disponible = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);
            $especie->biomasa_inicial =  ((($especie->peso_inicial) * ($especie->cantidad_inicial)) / 1000);

            if ($siembra->id == $especie->id_siembra) {
              $contador_esp++;
              $especie->cant_actual_especie += $especie->cant_actual;
              $siembra->cantidad_inicial += $especie->cantidad_inicial;
              $siembra->peso_ini += $especie->peso_inicial;
              $siembra->cant_actual += $especie->cant_actual;
              $siembra->peso_actual += $especie->peso_actual;
              $siembra->biomasa_inicial += $especie->biomasa_inicial;
              $siembra->biomasa_disponible += $especie->biomasa_disponible;
              $siembra->densidad_final = (number_format(($siembra->cant_actual / $especie->capacidad), 2, ',', ''));
              $siembra->carga_final = (number_format(($siembra->biomasa_disponible / $especie->capacidad), 2, ',', ''));

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

                  // $siembra->mortalidad = $especie->mortalidad;
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
              $siembra->mortalidad += $especie->mortalidad;
              $siembra->mortalidad_kg += $especie->mortalidad_kg;
              $siembra->mortalidad_porcentaje = (($siembra->mortalidad * 100) / $siembra->cantidad_inicial);
              $siembra->salida_biomasa = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->biomasa;;
              $siembra->salida_animales = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->cantidad;
              $siembra->incremento_biomasa += $especie->incremento_biomasa;
              $siembra->intervalo_tiempo = $especie->intervalo_tiempo;
              $siembra->porc_supervivencia_final = (($siembra->salida_animales * 100) / $siembra->cantidad_inicial);
            }
          }
        }
        for ($l = 0; $l < count($recursos_necesarios); $l++) {
          if ($siembra->id == $recursos_necesarios[$l]->id_siembra) {
            $siembra->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
            $siembra->horas_hombre += $recursos_necesarios[$l]->horas_hombre;
            $recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
            $siembra->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
            $recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
            $siembra->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
            $recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana) * $recursos_necesarios[$l]->costo_alimento;
            $siembra->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;

            if ($recursos_necesarios[$l]->conv_alimenticia > 0) {
              $recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
              $recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia, 2, ',', '');
              $siembra->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
            }
          }
        }
        $siembra->costo_minutos_hombre += ($siembra->minutos_hombre * $mh->costo);
        $siembra->costo_total_siembra = ($siembra->costo_minutos_hombre + $siembra->costo_total_alimento + $siembra->costo_total_recurso);

        if (($siembra->salida_biomasa) > 0) {
          $siembra->costo_produccion = $siembra->costo_total_siembra / $siembra->salida_biomasa;
        } else {
          $siembra->costo_produccion = 0;
        }
       
        if ($siembra->incr_bio_acum_conver > 0) {
          $siembra->conversion_alimenticia = ($siembra->cantidad_total_alimento) / ($siembra->incr_bio_acum_conver);
        }

        if ($siembra->incremento_biomasa > 0) {
          $siembra->conversion_alimenticia_siembra = $siembra->cantidad_total_alimento /  $siembra->incremento_biomasa;
        }

        if (($siembra->biomasa_disponible - $siembra->biomasa_inicial) > 0) {
          $siembra->conversion_alimenticia_parcial = $siembra->cantidad_total_alimento / ($siembra->biomasa_disponible - $siembra->biomasa_inicial);
        } else {
          $siembra->conversion_alimenticia_parcial = 0;
        }

        $siembra->bio_dispo_conver = ($siembra->biomasa_inicial + $siembra->incr_bio_acum_conver) - ($siembra->biomasa_disponible + $siembra->mortalidad_kg);
        $siembra->bio_dispo_alimen = (($siembra->incr_bio_acum_conver + $siembra->biomasa_inicial) - ($siembra->salida_biomasa + $siembra->mortalidad_kg));

        if (($siembra->bio_dispo_alimen) > 0) {
          $siembra->costo_produccion_parcial = $siembra->costo_total_siembra / $siembra->bio_dispo_alimen;
        } else {
          $siembra->costo_produccion_parcial = 0;
        }

        if ($siembra->salida_animales > 0 && $siembra->intervalo_tiempo > 0) {
          $siembra->ganancia_peso_dia = ((($siembra->salida_biomasa * 1000) / $siembra->salida_animales) / $siembra->intervalo_tiempo);
        }

        $siembra->contador_esp = $contador_esp;

        if (($siembra->contador_esp) > 0) {
          $siembra->peso_inicial = $siembra->peso_ini / $siembra->contador_esp;
        } else {
          $siembra->peso_inicial = 0;
        }

        if (($siembra->contador_esp) > 0) {
          $siembra->peso_actual_esp = $siembra->peso_actual / $siembra->contador_esp;
        } else {
          $siembra->peso_actual_esp = 0;
        }

        $siembra->conversion_alimenticia_siembra = number_format($siembra->conversion_alimenticia_siembra, 2, ',', '');
        $siembra->biomasa_disponible = number_format($siembra->biomasa_disponible, 2, ',', '');
        $siembra->mortalidad_kg = number_format($siembra->mortalidad_kg, 2, ',', '');
        $siembra->salida_animales = number_format($siembra->salida_animales, 2, ',', '');
        $siembra->incremento_biomasa = number_format($siembra->incremento_biomasa, 2, ',', '');
        $siembra->bio_dispo_conver = number_format($siembra->bio_dispo_conver, 2, ',', '');
        $siembra->bio_dispo_alimen = number_format($siembra->bio_dispo_alimen, 2, ',', '');
        $siembra->incr_bio_acum_conver = number_format($siembra->incr_bio_acum_conver, 2, ',', '');
        $siembra->ganancia_peso_dia = number_format($siembra->ganancia_peso_dia, 2, ',', '');
        $siembra->peso_inicial = number_format($siembra->peso_inicial, 2, ',', '');
        $siembra->mortalidad_porcentaje = number_format($siembra->mortalidad_porcentaje, 2, ',', '');
        $siembra->peso_actual_esp = number_format($siembra->peso_actual_esp, 2, ',', '');
        $siembra->horas_hombre = number_format($siembra->horas_hombre, 2, ',', '');
        $siembra->conversion_alimenticia = number_format($siembra->conversion_alimenticia, 2, ',', '');
        $siembra->conversion_alimenticia_parcial = number_format($siembra->conversion_alimenticia_parcial, 2, ',', '');
        $siembra->costo_produccion_parcial = number_format($siembra->costo_produccion_parcial, 2, ',', '');
        $siembra->costo_produccion = number_format($siembra->costo_produccion, 2, ',', '');
        $siembra->porc_supervivencia_final = number_format($siembra->porc_supervivencia_final, 2, ',', '');
        $siembra->costo_total_recurso = number_format($siembra->costo_total_recurso, 2, ',', '');
        $siembra->costo_total_alimento = number_format($siembra->costo_total_alimento, 2, ',', '');
        $siembra->costo_total_siembra = number_format($siembra->costo_total_siembra, 2, ',', '');
        // recursos_necesarios
        $aux_regs[] = [
          "biomasa_inicial" => $siembra->biomasa_inicial,
          "biomasa_disponible" => $siembra->biomasa_disponible,
          'bio_dispo_conver' => $siembra->bio_dispo_conver,
          'bio_dispo_alimen' => $siembra->bio_dispo_alimen,
          "carga_final" => $siembra->carga_final,
          "cantidad_inicial" => $siembra->cantidad_inicial,
          "cant_actual" => $siembra->cant_actual,
          "costo_minutosh" => $siembra->costo_minutos_hombre,
          "costo_total_recurso" => $siembra->costo_total_recurso,
          'cantidad_total_alimento' => $siembra->cantidad_total_alimento,
          "costo_total_alimento" => $siembra->costo_total_alimento,
          "costo_tot" => $siembra->costo_total_siembra,
          "costo_produccion" => $siembra->costo_produccion,
          "costo_produccion_parcial" => $siembra->costo_produccion_parcial,
          'conversion_alimenticia' => $siembra->conversion_alimenticia,
          'conversion_alimenticia_siembra' => $siembra->conversion_alimenticia_siembra,
          'conversion_alimenticia_parcial' => $siembra->conversion_alimenticia_parcial,
          "densidad_final" => $siembra->densidad_final,
          'ganancia_peso_dia' => $siembra->ganancia_peso_dia,
          "fecha_inicio" => $siembra->fecha_inicio,
          "horas_hombre" => $siembra->horas_hombre,
          "minutos_hombre" => $siembra->minutos_hombre,
          'incr_bio_acum_conver' => $siembra->incr_bio_acum_conver,
          'incremento_biomasa' => $siembra->incremento_biomasa,
          'intervalo_tiempo' => $siembra->intervalo_tiempo,
          "mortalidad" => $siembra->mortalidad,
          "mortalidad_kg" => $siembra->mortalidad_kg,
          "mortalidad_porcentaje" => $siembra->mortalidad_porcentaje,
          "nombre_siembra" => $siembra->nombre_siembra,
          "peso_inicial" => $siembra->peso_inicial,
          "peso_actual" => $siembra->peso_actual_esp,
          "salida_animales" => $siembra->salida_animales,
          "salida_biomasa" => $siembra->salida_biomasa,
          "porc_supervivencia_final" => $siembra->porc_supervivencia_final,
          'capacidad' => $siembra->capacidad

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
      foreach ($siembras as $siembra) {
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

            if ($siembra->id == $especie->id_siembra) {
              $contador_esp++;
              $especie->cant_actual_especie += $especie->cant_actual;
              $siembra->cantidad_inicial += $especie->cantidad_inicial;
              $siembra->peso_ini += $especie->peso_inicial;
              $siembra->cant_actual += $especie->cant_actual;
              $siembra->peso_actual += $especie->peso_actual;
              $siembra->biomasa_inicial += $especie->biomasa_inicial;
              $siembra->biomasa_disponible += $especie->biomasa_disponible;
              $siembra->densidad_final = (number_format(($siembra->cant_actual / $especie->capacidad), 2, ',', ''));
              $siembra->carga_final = (number_format(($siembra->biomasa_disponible / $especie->capacidad), 2, ',', ''));

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
                    if ($especie->ganancia_peso_dia > 0) {
                      $especie->ganancia_peso_dia = $especie->peso_incremento / $especie->intervalo_tiempo;
                    }
                    $especie->mortalidad_porcentaje =  (($especie->mortalidad * 100) / $especie->cantidad_inicial);
                  }
                }
              }
              $siembra->mortalidad += $especie->mortalidad;
              $siembra->mortalidad_kg += $especie->mortalidad_kg;
              $siembra->mortalidad_porcentaje = (($siembra->mortalidad * 100) / $siembra->cantidad_inicial);
              $siembra->salida_biomasa = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->biomasa;
              $siembra->salida_animales = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($especie->id_siembra)->cantidad;
              $siembra->incremento_biomasa += $especie->incremento_biomasa;
              $siembra->intervalo_tiempo = $especie->intervalo_tiempo;
              $siembra->porc_supervivencia_final = (($siembra->salida_animales * 100) / $siembra->cantidad_inicial);
            }
          }
        }
        for ($l = 0; $l < count($recursos_necesarios); $l++) {
          if ($siembra->id == $recursos_necesarios[$l]->id_siembra) {
            $siembra->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
            $siembra->horas_hombre += $recursos_necesarios[$l]->horas_hombre;
            $recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
            $siembra->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
            $recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
            $siembra->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
            $recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana) * $recursos_necesarios[$l]->costo_alimento;
            $siembra->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;

            if ($recursos_necesarios[$l]->conv_alimenticia > 0) {
              $recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
              $recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia, 2, ',', '');
              $siembra->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
            }
          }
        }
        $siembra->costo_minutos_hombre += ($siembra->minutos_hombre * $mh->costo);
        $siembra->costo_total_siembra = ($siembra->costo_minutos_hombre + $siembra->costo_total_alimento + $siembra->costo_total_recurso);
        if (($siembra->salida_biomasa) > 0) {
          $siembra->costo_produccion = $siembra->costo_total_siembra / $siembra->salida_biomasa;
        } else {
          $siembra->costo_produccion = 0;
        }

        if ($siembra->incr_bio_acum_conver > 0) {
          $siembra->conversion_alimenticia = ($siembra->cantidad_total_alimento) / ($siembra->incr_bio_acum_conver);
        }
        if ($siembra->incremento_biomasa > 0) {
          $siembra->conversion_alimenticia_siembra = $siembra->cantidad_total_alimento /  $siembra->incremento_biomasa;
        } else {
          $siembra->conversion_alimenticia_siembra = 0;
        }

        $siembra->bio_dispo_conver = ($siembra->biomasa_inicial + $siembra->incr_bio_acum_conver) - ($siembra->biomasa_disponible + $siembra->mortalidad_kg);
        $siembra->bio_dispo_alimen = (($siembra->incr_bio_acum_conver + $siembra->biomasa_inicial) - ($siembra->salida_biomasa + $siembra->mortalidad_kg));
      
				

        if (($siembra->bio_dispo_alimen) > 0) {
          $siembra->costo_produccion_parcial = $siembra->costo_total_siembra / $siembra->bio_dispo_alimen;
        } else {
          $siembra->costo_produccion_parcial = 0;
        }

        if (($siembra->biomasa_disponible - $siembra->biomasa_inicial) > 0) {
          $siembra->conversion_alimenticia_parcial = $siembra->cantidad_total_alimento / ($siembra->biomasa_disponible - $siembra->biomasa_inicial);
        } else {
          $siembra->conversion_alimenticia_parcial = 0;
        }
        $siembra->contador_esp = $contador_esp;
        $siembra->peso_inicial = $siembra->peso_ini / $siembra->contador_esp;
        $siembra->peso_actual_esp = $siembra->peso_actual / $siembra->contador_esp;
        $siembra->conversion_alimenticia_siembra = number_format($siembra->conversion_alimenticia_siembra, 2, ',', '');
        $siembra->biomasa_disponible = number_format($siembra->biomasa_disponible, 2, ',', '');
        $siembra->mortalidad_kg = number_format($siembra->mortalidad_kg, 2, ',', '');
        $siembra->salida_animales = number_format($siembra->salida_animales, 2, ',', '');
        $siembra->incremento_biomasa = number_format($siembra->incremento_biomasa, 2, ',', '');
        $siembra->bio_dispo_conver = number_format($siembra->bio_dispo_conver, 2, ',', '');
        $siembra->bio_dispo_alimen = number_format($siembra->bio_dispo_alimen, 2, ',', '');
        $siembra->incr_bio_acum_conver = number_format($siembra->incr_bio_acum_conver, 2, ',', '');
        $siembra->ganancia_peso_dia = number_format($siembra->ganancia_peso_dia, 2, ',', '');
        $siembra->peso_inicial = number_format($siembra->peso_inicial, 2, ',', '');
        $siembra->mortalidad_porcentaje = number_format($siembra->mortalidad_porcentaje, 2, ',', '');
        $siembra->peso_actual_esp = number_format($siembra->peso_actual_esp, 2, ',', '');
        $siembra->horas_hombre = number_format($siembra->horas_hombre, 2, ',', '');
        $siembra->conversion_alimenticia_parcial = number_format($siembra->conversion_alimenticia_parcial, 2, ',', '');
        $siembra->conversion_alimenticia = number_format($siembra->conversion_alimenticia, 2, ',', '');
        $siembra->costo_produccion = number_format($siembra->costo_produccion, 2, ',', '');
        $siembra->costo_produccion_parcial = number_format($siembra->costo_produccion_parcial, 2, ',', '');
        $siembra->porc_supervivencia_final = number_format($siembra->porc_supervivencia_final, 2, ',', '');
        $siembra->costo_total_recurso = number_format($siembra->costo_total_recurso, 2, ',', '');
        $siembra->costo_total_alimento = number_format($siembra->costo_total_alimento, 2, ',', '');
        $siembra->costo_total_siembra = number_format($siembra->costo_total_siembra, 2, ',', '');
        
        // recursos_necesarios
        $aux_regs[] = [
          "biomasa_inicial" => $siembra->biomasa_inicial,
          "biomasa_disponible" => $siembra->biomasa_disponible,
          'bio_dispo_conver' => $siembra->bio_dispo_conver,
          'bio_dispo_alimen' => $siembra->bio_dispo_alimen,
          "carga_final" => $siembra->carga_final,
          'capacidad' => $siembra->capacidad,
          "cantidad_inicial" => $siembra->cantidad_inicial,
          "cant_actual" => $siembra->cant_actual,
          "costo_minutosh" => $siembra->costo_minutos_hombre,
          "costo_total_recurso" => $siembra->costo_total_recurso,
          'cantidad_total_alimento' => $siembra->cantidad_total_alimento,
          "costo_total_alimento" => $siembra->costo_total_alimento,
          "costo_tot" => $siembra->costo_total_siembra,
          "costo_produccion" => $siembra->costo_produccion,
          "costo_produccion_parcial" => $siembra->costo_produccion_parcial,
          'conversion_alimenticia' => $siembra->conversion_alimenticia,
          'conversion_alimenticia_parcial' => $siembra->conversion_alimenticia_parcial,
          'conversion_alimenticia_siembra' => $siembra->conversion_alimenticia_siembra,
          "densidad_final" => $siembra->densidad_final,
          'ganancia_peso_dia' => $siembra->ganancia_peso_dia,
          "fecha_inicio" => $siembra->fecha_inicio,
          "horas_hombre" => $siembra->horas_hombre,
          "minutos_hombre" => $siembra->minutos_hombre,
          'incr_bio_acum_conver' => $siembra->incr_bio_acum_conver,
          'incremento_biomasa' => $siembra->incremento_biomasa,
          'intervalo_tiempo' => $siembra->intervalo_tiempo,
          "mortalidad" => $siembra->mortalidad,
          "mortalidad_kg" => $siembra->mortalidad_kg,
          "mortalidad_porcentaje" => $siembra->mortalidad_porcentaje,
          "nombre_siembra" => $siembra->nombre_siembra,
          "peso_inicial" => $siembra->peso_inicial,
          "peso_actual" => $siembra->peso_actual_esp,
          "salida_animales" => $siembra->salida_animales,
          "salida_biomasa" => $siembra->salida_biomasa,
          "porc_supervivencia_final" => $siembra->porc_supervivencia_final,
        ];
      }
    }
    return ['existencias' => $aux_regs];
  }
}
