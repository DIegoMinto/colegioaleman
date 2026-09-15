<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0.25in;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 7px;
        }

        .titulo {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .subtitulo {
            text-align: center;
            font-size: 8px;
            margin-bottom: 6px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            /* Importante: omitimos table-layout: fixed para que calcule según contenido */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
            font-size: 6.5px;
        }

        .th-dim-ser {
            background-color: #c6efce;
            font-weight: bold;
        }

        .th-dim-saber {
            background-color: #bdd7ee;
            font-weight: bold;
        }

        .th-dim-hacer {
            background-color: #fce4d6;
            font-weight: bold;
        }

        .th-dim-decidir {
            background-color: #ffe699;
            font-weight: bold;
        }

        .th-asistencia {
            background-color: #e0e7ff;
            font-weight: bold;
        }

        .th-promedio {
            background-color: #fff2cc;
            font-weight: bold;
        }

        /* Ajuste exacto al nombre más largo sin espacio desperdiciado */
        .nombre {
            text-align: left !important;
            white-space: nowrap !important;
            width: 1% !important;
            /* Fuerza a ajustarse exactamente al texto más largo */
            font-size: 6.5px;
            padding-left: 3px !important;
            padding-right: 5px !important;
        }

        .th-vertical {
            height: 85pt;
            padding: 0;
            position: relative;
        }

        .texto-rotado {
            position: absolute;
            bottom: 2px;
            left: 5pt;
            transform: rotate(-90deg);
            transform-origin: left bottom;
            white-space: nowrap;
            width: 80pt;
            font-size: 5.8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="titulo">{{ $asignacion->curso->nombre }} "{{ $asignacion->curso->paralelo }}" —
        {{ $asignacion->materia->nombre ?? '' }}
    </div>
    <div class="subtitulo">
        Trimestre: {{ $trimestre->nombres }} &nbsp;|&nbsp;
        Prof.: {{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellido_p }}
        {{ $asignacion->docente->persona->apellido_m }}
        @if($modo === 'blanco') &nbsp;|&nbsp; <strong>PLANILLA EN BLANCO</strong> @endif
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 10pt;">N°</th>
                <th rowspan="2" class="nombre">Apellidos y Nombres</th>

                <th colspan="{{ max($diasAsistencia->count(), 1) }}" class="th-asistencia">Asistencia</th>
                <th rowspan="2" class="th-asistencia col-promedio">Total</th>

                @foreach ($evaluaciones as $evaluacion)
                    @php
                        $dimClass = match ($evaluacion->tipo) {
                            'Ser' => 'th-dim-ser', 'Saber' => 'th-dim-saber',
                            'Hacer' => 'th-dim-hacer', 'Decidir' => 'th-dim-decidir', default => '',
                        };
                        $llevaPromedio = in_array($evaluacion->tipo, ['Ser', 'Saber', 'Hacer', 'Decidir']);
                        $colspan = $evaluacion->criterios->count() + ($llevaPromedio ? 1 : 0);
                    @endphp
                    <th colspan="{{ max($colspan, 1) }}" class="{{ $dimClass }}">
                        {{ $evaluacion->tipo }} ({{ $evaluacion->porcentaje }})
                    </th>
                @endforeach

                <th rowspan="2" class="th-promedio col-promedio">Prom.</th>
                <th rowspan="2" class="col-promedio">Entrevista</th>
            </tr>
            <tr>
                @foreach ($diasAsistencia as $dia)
                    <th class="th-asistencia th-vertical col-casilla">
                        <div class="texto-rotado">
                            {{ $dia->fecha ? \Carbon\Carbon::parse($dia->fecha)->format('d/m') : '-' }}
                        </div>
                    </th>
                @endforeach

                @foreach ($evaluaciones as $evaluacion)
                    @php
                        $dimClass = match ($evaluacion->tipo) {
                            'Ser' => 'th-dim-ser', 'Saber' => 'th-dim-saber',
                            'Hacer' => 'th-dim-hacer', 'Decidir' => 'th-dim-decidir', default => '',
                        };
                        $llevaPromedio = in_array($evaluacion->tipo, ['Ser', 'Saber', 'Hacer', 'Decidir']);
                    @endphp
                    @foreach ($evaluacion->criterios as $criterio)
                        <th class="{{ $dimClass }} th-vertical col-casilla">
                            <div class="texto-rotado">{{ $criterio->nombre ?: '' }}</div>
                        </th>
                    @endforeach
                    @if ($llevaPromedio)
                        <th class="{{ $dimClass }} col-promedio">Prom.</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiantes as $i => $estudiante)
                @php
                    $notasDelEstudiante = $calificaciones[$estudiante->id_estudiantes] ?? collect();
                    $promediosPorDimension = [];
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="nombre">
                        {{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->apellido_m }}
                        {{ $estudiante->persona->nombres }}
                    </td>

                    @foreach ($diasAsistencia as $dia)
                        <td class="col-casilla"></td>
                    @endforeach
                    <td class="col-promedio">
                        {{ $modo === 'llena' ? ($conteoAsistencias[$estudiante->id_estudiantes] ?? 0) : '' }}
                    </td>

                    @foreach ($evaluaciones as $evaluacion)
                        @php
                            $llevaPromedio = in_array($evaluacion->tipo, ['Ser', 'Saber', 'Hacer', 'Decidir']);
                            $notasIngresadas = [];
                        @endphp
                        @foreach ($evaluacion->criterios as $criterio)
                            @php
                                $nota = $modo === 'llena' ? ($notasDelEstudiante[$criterio->id_criterios]->nota ?? null) : null;
                                if ($nota !== null) {
                                    $notasIngresadas[] = (float) $nota;
                                }
                            @endphp
                            <td class="col-casilla">
                                {{ $nota !== null ? rtrim(rtrim(number_format($nota, 2, '.', ''), '0'), '.') : '' }}
                            </td>
                        @endforeach

                        @php
                            $promedioDim = count($notasIngresadas) > 0 ? array_sum($notasIngresadas) / count($notasIngresadas) : null;
                            $promediosPorDimension[$evaluacion->tipo] = $promedioDim;
                        @endphp
                        @if ($llevaPromedio)
                            <td class="col-promedio">{{ $promedioDim !== null ? number_format($promedioDim, 1, ',', '') : '' }}</td>
                        @endif
                    @endforeach

                    @php
                        $todasCompletas = $modo === 'llena' && collect($promediosPorDimension)->every(fn($p) => $p !== null);
                        $promedioFinal = $todasCompletas ? array_sum($promediosPorDimension) : null;
                    @endphp
                    <td class="th-promedio col-promedio">
                        {{ $promedioFinal !== null ? number_format($promedioFinal, 0) : '' }}
                    </td>
                    <td class="col-promedio"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>