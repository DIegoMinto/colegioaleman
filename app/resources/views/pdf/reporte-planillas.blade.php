<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Planillas</title>
    <style>
        @page {
            margin: 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #111;
        }

        .encabezado {
            display: table;
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .encabezado img {
            height: 50px;
        }

        .encabezado .titulo {
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }

        .encabezado h1 {
            font-size: 14px;
            margin: 0;
            text-transform: uppercase;
        }

        .encabezado p {
            font-size: 9px;
            color: #555;
            margin: 2px 0 0;
        }

        .resumen {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }

        .resumen .caja {
            display: table-cell;
            width: 33%;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .resumen .caja .numero {
            font-size: 18px;
            font-weight: bold;
        }

        .resumen .caja .etiqueta {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }

        .caja-completas .numero {
            color: #059669;
        }

        .caja-observaciones .numero {
            color: #b45309;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #eee;
            font-weight: bold;
        }

        .izq {
            text-align: left;
        }

        .estado-completa {
            background-color: #d1fae5;
            color: #065f46;
            font-weight: bold;
        }

        .estado-observaciones {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="encabezado">
        <img src="{{ public_path('images/escudo-colegio.png') }}">
        <div class="titulo">
            <h1>Reporte de Estado de Planillas</h1>
            <p>
                Trimestre {{ $trimestre->orden ?? '' }} &middot; Gestión {{ $trimestre->gestion }}
                @if($curso)
                    &middot; Curso: {{ $curso->nombre }} "{{ $curso->paralelo }}"
                @else
                    &middot; Todos los cursos
                @endif
                &middot; Generado el {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>

    <div class="resumen">
        <div class="caja">
            <div class="numero">{{ $totalMaterias }}</div>
            <div class="etiqueta">Total Materias</div>
        </div>
        <div class="caja caja-completas">
            <div class="numero">{{ $completas }}</div>
            <div class="etiqueta">Planillas Completas</div>
        </div>
        <div class="caja caja-observaciones">
            <div class="numero">{{ $conObservaciones }}</div>
            <div class="etiqueta">Con Observaciones</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="izq">Curso</th>
                <th class="izq">Materia</th>
                <th class="izq">Docente</th>
                <th>Estado</th>
                <th>Estudiantes con Observ.</th>
                <th>Sin Nota</th>
                <th>Notas en 0</th>
                <th>Dimensiones Incompletas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filas as $fila)
                <tr>
                    <td class="izq">{{ $fila['curso'] }}</td>
                    <td class="izq">{{ $fila['materia'] }}</td>
                    <td class="izq">{{ $fila['docente'] }}</td>
                    <td class="{{ $fila['estado'] === 'Completa' ? 'estado-completa' : 'estado-observaciones' }}">
                        {{ $fila['estado'] }}
                    </td>
                    <td>{{ $fila['estudiantes_con_observaciones'] }}</td>
                    <td>{{ $fila['sin_nota'] }}</td>
                    <td>{{ $fila['con_cero'] }}</td>
                    <td>{{ $fila['dimensiones_incompletas'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>