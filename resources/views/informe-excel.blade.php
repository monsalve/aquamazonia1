<?php
    header('Content-type: application/vnd.ms-excel;');
    header('Content-Disposition: attachment; filename=listado_siembras.xls');
?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Siembra</th>
            <th> Estado</th>
            <th>Tipo Actividad</th>
            <th>Fecha</th>
            <th>Horas hombre</th>
            <th>Recursos</th>
            <th>Costo</th>
            <th>Costo Acumulado</th>
            <th>Horas Hombre</th>
            <th>Alimentos</th>
            <th>Costo</th>
            <th>Costo acumulado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($recursosNecesarios as $rn)
            <tr>
                <td> {{ $rn->id}}</td>
                <td> {{ $rn->nombre_siembra}}</td>
                <td> 
                    @if ($rn->estado === 0)
                        Inactiva
                    @else
                        Activa
                    @endif
                
                </td>
                <td> {{ $rn->tipo_actividad }} </td>
                <td> {{ $rn->fecha_ra}}</td>                    
                <td> {{ $rn->horas_hombre }} </td>
                <td> {{ $rn->recurso }} </td>
                <td> {{ $rn->costo_r }} </td>
                <td> {{ $rn->costo_r_acum }} </td>
                <td> {{ $rn->horas_hombre }} </td>
                <td> {{ $rn->alimento }} </td>
                <td> {{ $rn->costo_a }} </td>
                <td> {{ $rn->costo_a_acum }} </td>
                
            </tr>
        @endforeach
    </tbody>
</table>
 