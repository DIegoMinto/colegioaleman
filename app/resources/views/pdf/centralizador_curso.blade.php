<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #222;
            font-size: 8px;
        }

        /* =========================================================
           ANCHO DE COLUMNAS
        ========================================================= */

        @php
            $cantidadMaterias = $asignaciones->count();

            /*
             * N°       = 3%
             * Nombre   = 38%
             * Promedio = 6%
             *
             * Materias = 53%
             */
            $anchoNumero = 3;
            $anchoNombre = 38;
            $anchoPromedio = 6;
            $anchoMaterias = 100 - $anchoNumero - $anchoNombre - $anchoPromedio;

            $anchoMateria = $cantidadMaterias > 0
                ? $anchoMaterias / $cantidadMaterias
                : 5;
        @endphp


        /* =========================================================
           TABLA
        ========================================================= */

        table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            table-layout: fixed;
        }

        th,
        td {
            border: 0.5px solid #9ca3af;
            padding: 0;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }


        /* =========================================================
           TITULO
        ========================================================= */

        .titulo {
            height: 22px;
            background: #ffffff;
            border: 0.7px solid #9ca3af;

            text-align: center;
            vertical-align: middle;

            font-size: 9px;
            font-weight: bold;
            color: #333;

            text-transform: uppercase;
        }

        .titulo-trimestre {
            font-size: 8px;
            font-weight: normal;
        }


        /* =========================================================
           N°
        ========================================================= */

        .numero {
            width: {{ $anchoNumero }}%;
            text-align: center;
        }

        .numero-header {
            height: 48px;

            background: #f3f4f6;

            vertical-align: middle;
            text-align: center;

            font-size: 7px;
            font-weight: bold;
            color: #555;

            text-transform: uppercase;
        }

        .numero-dato {
            height: 19px;

            vertical-align: middle;
            text-align: center;

            font-size: 7.5px;
            color: #555;
        }


        /* =========================================================
           APELLIDOS Y NOMBRES
        ========================================================= */

        .nombres {
            width: {{ $anchoNombre }}%;
        }

        .nombres-header {
            height: 48px;

            background: #e7eef2;

            vertical-align: middle;
            text-align: center;

            padding: 0 5px;

            color: #596873;

            font-size: 9px;
            font-weight: bold;

            text-transform: uppercase;
        }

        .nombre-dato {
            height: 19px;

            padding: 0 5px;

            vertical-align: middle;
            text-align: left;

            /*
             * MUY IMPORTANTE:
             * dejamos que el nombre utilice TODO el ancho.
             */
            white-space: nowrap;

            font-size: 7.8px;
            font-weight: normal;

            color: #333;

            text-transform: uppercase;
        }


        /* =========================================================
           MATERIAS
        ========================================================= */

        .materia {
            width: {{ $anchoMateria }}%;
        }

        .materia-header {
            height: 48px;

            background: #f3f4f6;

            position: relative;

            padding: 0;

            vertical-align: bottom;

            overflow: hidden;
        }


        /*
         * ZONA SUPERIOR:
         *
         * Aquí vive ÚNICAMENTE el nombre de la materia.
         */
        .materia-nombre-zona {
            position: absolute;

            top: 2px;
            left: 0;

            width: 100%;
            height: 34px;

            overflow: hidden;
        }

        .materia-nombre {
            position: absolute;

            left: 50%;
            top: 17px;

            /*
             * El ancho de este elemento es vertical
             * después de rotarlo.
             */
            width: 70px;
            height: 8px;

            margin-left: -35px;

            text-align: center;

            white-space: nowrap;

            font-size: 6px;
            line-height: 8px;

            font-weight: bold;
            color: #444;

            text-transform: uppercase;

            transform: rotate(-90deg);
            -webkit-transform: rotate(-90deg);

            transform-origin: center center;
            -webkit-transform-origin: center center;
        }


        /*
         * ZONA INFERIOR:
         *
         * Completamente independiente del nombre.
         */
        .materia-trimestre-zona {
            position: absolute;

            left: 0;
            bottom: 0;

            width: 100%;
            height: 12px;

            border-top: 0.5px solid #d1d5db;

            background: #fafafa;
        }

        .materia-trimestre {
            display: block;

            width: 100%;

            text-align: center;

            font-size: 5px;
            line-height: 11px;

            font-weight: bold;

            color: #555;

            white-space: nowrap;
            text-transform: uppercase;
        }


        /* =========================================================
           NOTAS
        ========================================================= */

        .nota {
            height: 19px;

            text-align: center;
            vertical-align: middle;

            font-size: 8px;
            font-weight: normal;

            color: #333;
        }

        .nota-baja {
            color: #c62828;
            font-weight: bold;
        }

        .nota-vacia {
            color: #b5b5b5;
        }


        /* =========================================================
           PROMEDIO
        ========================================================= */

        .promedio {
            width: {{ $anchoPromedio }}%;
        }

        .promedio-header {
            height: 48px;

            background: #f3f4f6;

            text-align: center;
            vertical-align: middle;

            font-size: 7px;
            font-weight: bold;

            color: #555;

            text-transform: uppercase;
        }

        .promedio-dato {
            height: 19px;

            text-align: center;
            vertical-align: middle;

            font-size: 8px;
            font-weight: bold;

            color: #333;
        }

        .promedio-reprobado {
            color: #c62828;
            background: #fff1f1;
        }


        /* =========================================================
           FILAS ALTERNADAS
        ========================================================= */

        tbody tr:nth-child(even) td {
            background: #fafafa;
        }

        /*
         * Pero las notas reprobadas conservan rojo.
         */
        tbody tr:nth-child(even) td.nota-baja {
            background: #fff1f1;
        }

        tbody tr:nth-child(even) td.promedio-reprobado {
            background: #fff1f1;
        }


        /* =========================================================
           PIE
        ========================================================= */

        .pie {
            margin-top: 4px;

            text-align: right;

            font-size: 5.5px;

            color: #999;
        }

    </style>
</head>


<body>

    <table>

        {{-- =====================================================
             CABECERA
        ====================================================== --}}

        <thead>

            <tr>

                <th
                    colspan="{{ $cantidadMaterias + 3 }}"
                    class="titulo"
                >

                    CENTRALIZADOR DE CALIFICACIONES

                    <span class="titulo-trimestre">
                        — {{ strtoupper($curso->nombre) }}
                        '{{ $curso->paralelo }}'
                        — {{ strtoupper($trimestreCorto) }} TRIMESTRE
                        — GESTIÓN {{ $trimestre->gestion }}
                    </span>

                </th>

            </tr>


            <tr>

                {{-- N° --}}
                <th class="numero numero-header">
                    N°
                </th>


                {{-- NOMBRES --}}
                <th class="nombres nombres-header">
                    Apellidos y Nombres
                </th>


                {{-- MATERIAS --}}
                @foreach ($asignaciones as $asignacion)

                    <th class="materia materia-header">

                        {{-- Zona exclusiva para nombre --}}
                        <div class="materia-nombre-zona">

                            <span class="materia-nombre">
                                {{ strtoupper($asignacion->materia->nombre) }}
                            </span>

                        </div>


                        {{-- Zona exclusiva para trimestre --}}
                        <div class="materia-trimestre-zona">

                            <span class="materia-trimestre">
                                {{ strtoupper($trimestreCorto) }} Trim.
                            </span>

                        </div>

                    </th>

                @endforeach


                {{-- PROMEDIO --}}
                <th class="promedio promedio-header">
                    Promedio
                </th>

            </tr>

        </thead>


        {{-- =====================================================
             ESTUDIANTES
        ====================================================== --}}

        <tbody>

            @foreach ($estudiantes as $i => $estudiante)

                @php
                    $prom =
                        $promedioGeneral[$estudiante->id_estudiantes]
                        ?? null;
                @endphp


                <tr>

                    {{-- N° --}}
                    <td class="numero numero-dato">
                        {{ $i + 1 }}
                    </td>


                    {{-- NOMBRE --}}
                    <td class="nombres nombre-dato">

                        {{ $estudiante->persona->apellido_p }}
                        {{ $estudiante->persona->apellido_m }}
                        {{ $estudiante->persona->nombres }}

                    </td>


                    {{-- NOTAS --}}
                    @foreach ($asignaciones as $asignacion)

                        @php
                            $nota =
                                $promedios[
                                    $estudiante->id_estudiantes
                                ][$asignacion->id_asignaciones]
                                ?? null;
                        @endphp

                        <td
                            class="materia nota
                            {{
                                $nota === null
                                    ? 'nota-vacia'
                                    : ($nota < 51
                                        ? 'nota-baja'
                                        : '')
                            }}"
                        >

                            {{ $nota ?? '' }}

                        </td>

                    @endforeach


                    {{-- PROMEDIO --}}
                    <td
                        class="promedio promedio-dato
                        {{
                            $prom === null
                                ? 'nota-vacia'
                                : ($prom < 51
                                    ? 'promedio-reprobado'
                                    : '')
                        }}"
                    >

                        {{ $prom ?? '' }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    <div class="pie">
        Centralizador de Calificaciones · Gestión {{ $trimestre->gestion }}
    </div>

</body>

</html>