<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Centralizador de Notas</title>
    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000000;
            font-size: 13px;
        }

        /* Encabezado institucional compacto */
        .header-box {
            width: 100%;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
        }

        .header-info {
            font-size: 12px;
            color: #334155;
            margin-top: 4px;
        }

        /* Tabla del Centralizador enfocada en legibilidad */
        .tabla-centralizador {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tabla-centralizador th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            padding: 8px 6px;
            border: 1px solid #0f172a;
        }

        .tabla-centralizador th.th-nombre {
            text-align: left;
            padding-left: 10px;
        }

        .tabla-centralizador td {
            padding: 7px 6px;
            font-size: 13px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        /* Columna de numeración */
        .td-num {
            text-align: center;
            width: 35px;
            font-weight: bold;
            color: #475569;
        }

        /* Columna de nombres */
        .td-nombre {
            text-align: left;
            padding-left: 10px !important;
            font-weight: bold;
            color: #0f172a;
        }

        /* Columnas de notas */
        .td-nota {
            text-align: center;
            width: 85px;
            font-size: 14px;
            font-weight: bold;
        }

        .td-promedio {
            text-align: center;
            width: 95px;
            font-size: 14px;
            font-weight: bold;
            background-color: #f1f5f9;
            color: #0f172a;
        }

        /* Filas intercaladas para no perder la línea al leer */
        .tabla-centralizador tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .guion {
            color: #94a3b8;
            font-weight: normal;
        }
    </style>
</head>

<body>

    <div class="header-box">
        <div class="header-title">CENTRALIZADOR DE NOTAS</div>
        <div class="header-info">
            <strong>Curso:</strong>
            {{ $asignacion->curso->nombre }} "{{ $asignacion->curso->paralelo }}" de {{ $asignacion->curso->nivel }}
            <strong>Materia:</strong> {{ $asignacion->materia->area->nombre ?? $asignacion->materia->nombre ?? '-' }} |
            <strong>Gestión:</strong> {{ $asignacion->gestion }}
        </div>
        <div class="header-info">
            <strong>Profesor:</strong> {{ $asignacion->docente->persona->nombres }}
            {{ $asignacion->docente->persona->apellido_p }} {{ $asignacion->docente->persona->apellido_m }}
        </div>
    </div>

    <table class="tabla-centralizador">
        <thead>
            <tr>
                <th class="td-num">N°</th>
                <th class="th-nombre">Apellidos y Nombres</th>
                @foreach ($trimestres as $trimestre)
                    <th>{{ $trimestre->nombres }}</th>
                @endforeach
                <th>Prom. Anual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiantes as $i => $estudiante)
                <tr>
                    <td class="td-num">{{ $i + 1 }}</td>
                    <td class="td-nombre">
                        {{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->apellido_m }}
                        {{ $estudiante->persona->nombres }}
                    </td>
                    @foreach ($trimestres as $trimestre)
                        @php
                            $promedio = $promediosPorTrimestre[$estudiante->id_estudiantes][$trimestre->id_trimestres] ?? null;
                        @endphp
                        <td class="td-nota">
                            {!! $promedio !== null ? $promedio : '<span class="guion">-</span>' !!}
                        </td>
                    @endforeach
                    <td class="td-promedio">
                        {!! $promedioAnual[$estudiante->id_estudiantes] ?? '<span class="guion">-</span>' !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>