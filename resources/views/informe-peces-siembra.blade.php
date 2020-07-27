<?php
    header('Content-type: application/vnd.ms-excel;');
    header('Content-Disposition: attachment; filename=listado_peces_siembras.xls');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <!-- <th>#</th> -->
                <th>Siembra</th>
                <th>Especie</th>
                <th>Tipo Registro</th>
                <th>Fecha</th>
                <th>Tiempo</th>
                <th>lote</th>
                <th>Cantidad Inicial</th>
                <th>Cantidad Pescada</th> 
                <th>Mortalidad</th>
                <th>Cantidad Actual</th>
                <th>Peso Inicial</th>
                <th>Peso Ganado</th>
                <th>Peso Actual</th>
                <th>Biomasa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $rps)
                <tr>
                    <td> {{ $rps->id_siembra}}</td>
                    <td> {{ $rps->especie}}</td>
                    <td> {{ $rps->tipo_registro }} </td>
                    <td> {{ $rps->fecha_registro}}</td>                    
                    <td> {{ $rps->tiempo }} </td>
                    <td> {{ $rps->lote }} </td>
                    <td> {{ $rps->cantidad_inicial }} </td>
                    <td> {{ $rps->cantidad_pesca }} </td>
                    <td> {{ $rps->mortalidad }} </td>
                    <td> {{ $rps->cant_actual }} </td>
                    
                    <td> {{ $rps->peso_inicial }} </td>
                    <td> {{ $rps->peso_ganado }} </td>
                    <td> {{ $rps->peso_actual }} </td>
                    <td> {{ $rps->biomasa }} </td>
                    
                    
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>
