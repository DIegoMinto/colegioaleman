@extends('layouts.app')

@section('title', 'Planilla de calificaciones')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/planilla.css') }}">

    <div class="planilla-wrapper">

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
                <td class="info-label">Trimestre</td>
                <td class="info-value">{{ $trimestre->nombres }}</td>
            </tr>
            <tr>
                <td class="info-label">Prof.</td>
                <td class="prof-value" colspan="5">
                    {{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellido_p }}
                    {{ $asignacion->docente->persona->apellido_m }}
                </td>
            </tr>
        </table>

        @if (session('exito'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800 my-3">
                {{ session('exito') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 my-3">
                <strong>Hay errores:</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Panel de Auditoría -->
        <div id="panel-auditoria" class="hidden mb-6 rounded-lg border-2 border-amber-400 bg-amber-50 p-4 shadow-sm">
            @if(count($irregularidades) > 0)
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 font-bold text-amber-900 text-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Observaciones e Irregularidades Detectadas ({{ count($irregularidades) }} estudiantes)</span>
                    </div>
                    <button type="button" onclick="toggleAuditoria()"
                        class="text-amber-800 hover:text-amber-950 font-bold text-sm">
                        ✕ Cerrar
                    </button>
                </div>

                <p class="text-xs text-amber-800 mb-3">
                    Revisa los siguientes casilleros para evitar errores en el promedio final o centralizado:
                </p>

                <div class="max-h-60 overflow-y-auto space-y-2 pr-2">
                    @foreach($irregularidades as $obs)
                        <div class="p-3 bg-white rounded border border-amber-200 text-xs text-slate-800">
                            <div class="font-bold text-slate-900 text-sm">{{ $obs['nombre'] }}</div>

                            @if(!empty($obs['sin_nota']))
                                <div class="mt-1 text-red-700 bg-red-100 px-2 py-1 rounded font-medium">
                                    <strong>Sin Nota:</strong> {{ implode(', ', $obs['sin_nota']) }}
                                </div>
                            @endif

                            @if(!empty($obs['con_cero']))
                                <div class="mt-1 text-amber-800 bg-amber-100 px-2 py-1 rounded font-medium">
                                    <strong>Nota en Cero:</strong> {{ implode(', ', $obs['con_cero']) }}
                                </div>
                            @endif

                            @if(!empty($obs['dimensiones_incompletas']))
                                <div class="mt-1 text-xs text-slate-600">
                                    <strong>Dimensión Incompleta:</strong> {{ implode(', ', $obs['dimensiones_incompletas']) }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-between text-emerald-800">
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span><strong>Planilla Impecable:</strong> No se encontraron casillas pendientes ni ceros atípicos en
                            las actividades configuradas.</span>
                    </div>
                    <button type="button" onclick="toggleAuditoria()" class="font-bold text-sm">✕</button>
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('planilla.store', [$asignacion, $trimestre]) }}">
            @csrf

            <!-- Div contenedor que limita el scroll horizontal únicamente a la tabla de notas -->
            <div class="tabla-scroll-container">
                <table class="planilla-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="sticky-col-num">N°</th>
                            <th rowspan="2" class="sticky-col-nombre">Apellidos y Nombres</th>
                            <th colspan="{{ $diasAsistencia->count() }}" class="th-dimension-asistencia toggle-asistencia">
                                Asistencia
                            </th>
                            <th rowspan="2" class="th-porc-asistencia toggle-asistencia">Asis.</th>

                            @foreach ($evaluaciones as $evaluacion)
                                @php
                                    $dimClass = match ($evaluacion->tipo) {
                                        'Ser' => 'th-dimension-ser',
                                        'Saber' => 'th-dimension-saber',
                                        'Hacer' => 'th-dimension-hacer',
                                        'Decidir' => 'th-dimension-decidir',
                                        default => 'th-dimension-ser',
                                    };
                                    $llevaPromedio = in_array($evaluacion->tipo, ['Ser', 'Saber', 'Hacer']);
                                    $colspan = $evaluacion->criterios->count() + ($llevaPromedio ? 1 : 0);
                                @endphp
                                <th colspan="{{ max($colspan, 1) }}" class="{{ $dimClass }}">
                                    {{ $evaluacion->tipo }} ({{ $evaluacion->porcentaje }} pts)
                                </th>
                            @endforeach

                            <th rowspan="2" class="th-promedio-final">Promedio</th>
                            <th rowspan="2" class="th-entrevista">Entrevista</th>
                        </tr>

                        <tr>
                            @foreach ($diasAsistencia as $dia)
                                <th class="th-dimension-asistencia th-vertical toggle-asistencia">
                                    <input type="text" class="input-fecha-dia" maxlength="5"
                                        name="fechas_dias[{{ $dia->id_dias_asistencia }}]"
                                        value="{{ $dia->fecha ? \Carbon\Carbon::parse($dia->fecha)->format('d/m') : '' }}"
                                        title="Fecha de esta actividad (opcional)">
                                </th>
                            @endforeach
                            @foreach ($evaluaciones as $evaluacion)
                                @php
                                    $dimClass = match ($evaluacion->tipo) {
                                        'Ser' => 'th-dimension-ser',
                                        'Saber' => 'th-dimension-saber',
                                        'Hacer' => 'th-dimension-hacer',
                                        'Decidir' => 'th-dimension-decidir',
                                        default => 'th-dimension-ser',
                                    };
                                    $llevaPromedio = in_array($evaluacion->tipo, ['Ser', 'Saber', 'Hacer']);
                                @endphp

                                @foreach ($evaluacion->criterios as $criterio)
                                    <th class="{{ $dimClass }} th-vertical">
                                        <input type="text" class="input-nombre-criterio"
                                            name="nombres_criterios[{{ $criterio->id_criterios }}]" value="{{ $criterio->nombre }}"
                                            title="Haz clic para renombrar">
                                    </th>
                                @endforeach

                                @if ($llevaPromedio)
                                    <th class="th-promedio-dim">{{ $evaluacion->porcentaje }}</th>
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
                                <td class="sticky-col-num">{{ $i + 1 }}</td>
                                <td class="sticky-col-nombre"
                                    title="{{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->apellido_m }} {{ $estudiante->persona->nombres }}">
                                    {{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->apellido_m }}
                                    {{ $estudiante->persona->nombres }}
                                </td>

                                @foreach ($diasAsistencia as $dia)
                                    @php
                                        $marcaAsistencia = $asistencias[$estudiante->id_estudiantes][$dia->id_dias_asistencia] ?? null;
                                        $estaPresente = $marcaAsistencia?->estado === 'presente';
                                    @endphp
                                    <td class="td-dimension-asistencia toggle-asistencia">
                                        <input type="hidden"
                                            name="asistencia[{{ $estudiante->id_estudiantes }}][{{ $dia->id_dias_asistencia }}]"
                                            value="0">
                                        <input type="checkbox" class="input-asistencia"
                                            name="asistencia[{{ $estudiante->id_estudiantes }}][{{ $dia->id_dias_asistencia }}]"
                                            value="1" {{ $estaPresente ? 'checked' : '' }}>
                                    </td>
                                @endforeach

                                <td class="td-porc-asistencia toggle-asistencia">
                                    {{ $conteoAsistencias[$estudiante->id_estudiantes] ?? 0 }}
                                </td>

                                @foreach ($evaluaciones as $evaluacion)
                                    @php
                                        $dimClass = match ($evaluacion->tipo) {
                                            'Ser' => 'td-dimension-ser',
                                            'Saber' => 'td-dimension-saber',
                                            'Hacer' => 'td-dimension-hacer',
                                            default => '',
                                        };
                                        $llevaPromedio = in_array($evaluacion->tipo, ['Ser', 'Saber', 'Hacer']);
                                        $notasIngresadas = [];
                                    @endphp

                                    @foreach ($evaluacion->criterios as $criterio)
                                        @php
                                            $nota = $notasDelEstudiante[$criterio->id_criterios]->nota ?? null;
                                            $valorInput = $nota !== null ? rtrim(rtrim(number_format($nota, 2, '.', ''), '0'), '.') : '';

                                            if ($nota !== null && $nota !== '') {
                                                $notasIngresadas[] = (float) $nota;
                                            }

                                            $claseAlerta = '';
                                            if ($nota === null) {
                                                $claseAlerta = 'bg-red-50 border-red-300 focus:ring-red-500';
                                            } elseif ((float) $nota === 0.0) {
                                                $claseAlerta = 'bg-amber-100 border-amber-400 font-bold text-amber-900';
                                            }
                                        @endphp
                                        <td class="{{ $dimClass }}">
                                            <input type="number" step="0.5" min="0" max="{{ $criterio->puntaje_maximo }}"
                                                class="input-nota {{ $claseAlerta }}"
                                                name="notas[{{ $estudiante->id_estudiantes }}][{{ $criterio->id_criterios }}]"
                                                value="{{ $valorInput }}" placeholder="-">
                                        </td>
                                    @endforeach

                                    @php
                                        $promedioDim = count($notasIngresadas) > 0
                                            ? array_sum($notasIngresadas) / count($notasIngresadas)
                                            : null;
                                        $promediosPorDimension[$evaluacion->tipo] = $promedioDim;
                                    @endphp

                                    @if ($llevaPromedio)
                                        <td class="td-promedio-dim">
                                            @if ($promedioDim !== null)
                                                {{ number_format($promedioDim, 1, ',', '') }}
                                            @else
                                                <span class="td-pendiente">-</span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                <td class="td-promedio-final">
                                    @php
                                        $todasCompletas = collect($promediosPorDimension)->every(fn($p) => $p !== null);
                                        $promedioFinal = $todasCompletas ? array_sum($promediosPorDimension) : null;
                                    @endphp
                                    @if ($promedioFinal !== null)
                                        {{ number_format($promedioFinal, 0) }}
                                    @else
                                        <span class="td-pendiente">-</span>
                                    @endif
                                </td>

                                <td class="td-entrevista">
                                    <input type="number" step="0.5" min="0" max="100" class="input-entrevista">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <button type="submit" class="btn-primary">Guardar Cambios</button>

                @php $cantInconsistencias = count($irregularidades); @endphp
                <button type="button" onclick="toggleAuditoria()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg shadow transition text-white {{ $cantInconsistencias > 0 ? 'bg-amber-500 hover:bg-amber-600' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Auditar Planilla</span>
                    <span class="bg-white text-slate-900 font-bold text-xs px-2 py-0.5 rounded-full shadow">
                        {{ $cantInconsistencias }}
                    </span>
                </button>

                <button type="button" onclick="toggleAsistencia()" class="btn-secondary" id="btn-toggle-asistencia">
                    Ocultar Asistencia
                </button>

                <a href="{{ route('planilla.pdf', [$asignacion, $trimestre, 'modo' => 'blanco']) }}" target="_blank"
                    class="btn-secondary">
                    Imprimir Vacía
                </a>

                <a href="{{ route('planilla.pdf', [$asignacion, $trimestre, 'modo' => 'llena']) }}" target="_blank"
                    class="btn-secondary">
                    Imprimir con Notas
                </a>

                <a href="{{ route('planilla.centralizador', [$asignacion, $trimestre]) }}" class="btn-secondary">
                    Ver Centralizador
                </a>
            </div>
        </form>

        <script>
            function toggleAuditoria() {
                const panel = document.getElementById('panel-auditoria');
                if (panel) {
                    panel.classList.toggle('hidden');
                    if (!panel.classList.contains('hidden')) {
                        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            }
            function toggleAsistencia() {
                const oculto = sessionStorage.getItem('asistencia_oculta') === '1';
                document.querySelectorAll('.toggle-asistencia').forEach(el => {
                    el.classList.toggle('hidden', !oculto);
                });
                sessionStorage.setItem('asistencia_oculta', oculto ? '0' : '1');
                document.getElementById('btn-toggle-asistencia').textContent =
                    oculto ? 'Ocultar Asistencia' : 'Mostrar Asistencia';
            }

            document.addEventListener('DOMContentLoaded', () => {
                if (sessionStorage.getItem('asistencia_oculta') === '1') {
                    document.querySelectorAll('.toggle-asistencia').forEach(el => el.classList.add('hidden'));
                    document.getElementById('btn-toggle-asistencia').textContent = 'Mostrar Asistencia';
                }
            });
        </script>
    </div>
@endsection