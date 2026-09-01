@extends('layouts.app')

@section('title', 'Centralizador')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/planilla.css') }}">
    <div class="planilla-wrapper space-y-6">

        <!-- Encabezado de la planilla -->
        <table class="info-table">
            <tr>
                <td rowspan="2" class="info-logo bg-brand-900">
                    <img src="{{ asset('images/escudo-horizontal.png') }}" alt="Escudo del colegio">
                </td>
                <td class="info-label">Curso</td>
                <td class="info-value">
                    {{ $asignacion->curso->nombre }} "{{ $asignacion->curso->paralelo }}" de {{ $asignacion->curso->nivel }}
                </td>
                <td class="info-label">Área</td>
                <td class="info-value">
                    {{ $asignacion->materia->area->nombre ?? $asignacion->materia->tipo->nombre ?? '-' }}
                </td>
                <td class="info-label">Gestión</td>
                <td class="info-value">{{ $asignacion->gestion }}</td>
            </tr>
            <tr>
                <td class="info-label">Prof.</td>
                <td class="prof-value" colspan="5">
                    {{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellido_p }}
                    {{ $asignacion->docente->persona->apellido_m }}
                </td>
            </tr>
        </table>

        <!-- Tabla centralizadora principal -->
        <div class="planilla-scroll">
            <table class="planilla-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Apellidos y Nombres</th>
                        @foreach ($trimestres as $trimestre)
                            <th>{{ $trimestre->nombres }}</th>
                        @endforeach
                        <th class="th-promedio-final">Prom. Anual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudiantes as $i => $estudiante)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td
                                title="{{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->apellido_m }} {{ $estudiante->persona->nombres }}">
                                {{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->apellido_m }}
                                {{ $estudiante->persona->nombres }}
                            </td>
                            @foreach ($trimestres as $trimestre)
                                @php
                                    $promedio = $promediosPorTrimestre[$estudiante->id_estudiantes][$trimestre->id_trimestres] ?? null;
                                @endphp
                                <td>{{ $promedio !== null ? $promedio : '-' }}</td>
                            @endforeach
                            <td class="td-promedio-final">
                                {{ $promedioAnual[$estudiante->id_estudiantes] ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

            <!-- 1. TABLA CON PORCENTAJES DE APROBADOS Y REPROBADOS -->
            @php
                $totV = $estadisticas['varones']['aprobados'] + $estadisticas['varones']['reprobados'];
                $totM = $estadisticas['mujeres']['aprobados'] + $estadisticas['mujeres']['reprobados'];
                $totG = $totV + $totM;

                $aprobG = $estadisticas['varones']['aprobados'] + $estadisticas['mujeres']['aprobados'];
                $reprobG = $estadisticas['varones']['reprobados'] + $estadisticas['mujeres']['reprobados'];
            @endphp

            <div class="table-container">
                <table class="table-custom text-center">
                    <thead>
                        <tr>
                            <th class="text-left">Grupo</th>
                            <th colspan="2">Aprobados</th>
                            <th colspan="2">Reprobados</th>
                        </tr>
                        <tr class="text-xs bg-gray-50 border-b">
                            <th></th>
                            <th>Cant.</th>
                            <th>%</th>
                            <th>Cant.</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left font-medium">Varones</td>
                            <td>{{ $estadisticas['varones']['aprobados'] }}</td>
                            <td class="text-gray-600">
                                {{ $totV > 0 ? round(($estadisticas['varones']['aprobados'] / $totV) * 100, 1) : 0 }}%
                            </td>
                            <td>{{ $estadisticas['varones']['reprobados'] }}</td>
                            <td class="text-gray-600">
                                {{ $totV > 0 ? round(($estadisticas['varones']['reprobados'] / $totV) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-medium">Mujeres</td>
                            <td>{{ $estadisticas['mujeres']['aprobados'] }}</td>
                            <td class="text-gray-600">
                                {{ $totM > 0 ? round(($estadisticas['mujeres']['aprobados'] / $totM) * 100, 1) : 0 }}%
                            </td>
                            <td>{{ $estadisticas['mujeres']['reprobados'] }}</td>
                            <td class="text-gray-600">
                                {{ $totM > 0 ? round(($estadisticas['mujeres']['reprobados'] / $totM) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        <tr class="font-bold border-t bg-gray-50">
                            <td class="text-left">Total</td>
                            <td>{{ $aprobG }}</td>
                            <td class="text-gray-800">{{ $totG > 0 ? round(($aprobG / $totG) * 100, 1) : 0 }}%</td>
                            <td>{{ $reprobG }}</td>
                            <td class="text-gray-800">{{ $totG > 0 ? round(($reprobG / $totG) * 100, 1) : 0 }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 2. CUADRO DE APROVECHAMIENTO -->
            <div class="border border-sky-400 rounded-lg overflow-hidden bg-sky-100 p-3 shadow-sm">
                <div
                    class="bg-sky-300 text-sky-950 font-bold uppercase text-center py-2 border border-sky-400 mb-3 tracking-wide">
                    Cuadro de Aprovechamiento
                </div>
                <div class="grid grid-cols-4 gap-2 text-center">
                    @foreach ($trimestres as $tri)
                        <div class="bg-white border border-sky-300 rounded overflow-hidden">
                            <div class="bg-sky-200 text-xs font-bold py-1 px-1 border-b border-sky-300 truncate">
                                {{ strtoupper($tri->nombres) }}
                            </div>
                            <div class="text-lg font-bold py-3 text-sky-900">
                                {{ number_format($aprovechamientoTrimestral[$tri->id_trimestres] ?? 0, 2, ',', '.') }}
                            </div>
                        </div>
                    @endforeach

                    <!-- Casilla Anual -->
                    <div class="bg-white border border-sky-300 rounded overflow-hidden">
                        <div class="bg-sky-200 text-xs font-bold py-1 px-1 border-b border-sky-300">
                            ANUAL
                        </div>
                        <div class="text-lg font-bold py-3 text-sky-900">
                            {{ number_format($aprovechamientoAnual ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="pt-4 flex items-center gap-3">
            <a href="{{ route('centralizador.pdf', [$asignacion, $trimestre]) }}" target="_blank"
                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Descargar PDF
            </a>

            <a href="{{ route('planilla.show', [$asignacion, $trimestre]) }}" class="btn-secondary">
                Volver a la planilla
            </a>
        </div>

    </div>
@endsection