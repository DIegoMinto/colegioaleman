@extends('layouts.app')

@section('title', 'Cursos Académicos')

@section('content')
    <div class="space-y-6 pb-12">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-5 rounded-xl border border-gray-200/80 shadow-sm">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-brand-900/10 text-brand-900 uppercase tracking-wider">
                        Gestión {{ $trimestre->gestion ?? date('Y') }}
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="text-xs font-medium text-gray-500">Periodo Actual</span>
                </div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight mt-1">Cursos y Materias Asignadas</h1>
                <p class="text-xs text-gray-500 mt-0.5">
                    Panel general de gestión académica, listas de alumnos y evaluaciones por asignatura.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                @if (auth()->user()->role->nombre !== 'Profesor')
                    <a href="{{ route('reportes.planillas.general', $trimestre) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-brand-900 hover:bg-brand-950 text-white text-xs font-semibold transition-all shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        <span>Reporte General</span>
                    </a>
                @endif
                <select id="filtro-nivel" class="rounded-lg border border-gray-300 bg-gray-50/50 px-2.5 py-2 text-xs text-gray-700 outline-none focus:bg-white focus:border-brand-900 focus:ring-2 focus:ring-brand-900/10">
                    <option value="todos">Todos los niveles</option>
                    <option value="primaria">Solo Primaria</option>
                    <option value="secundaria">Solo Secundaria</option>
                </select>

                <select id="filtro-paralelo" class="rounded-lg border border-gray-300 bg-gray-50/50 px-2.5 py-2 text-xs text-gray-700 outline-none focus:bg-white focus:border-brand-900 focus:ring-2 focus:ring-brand-900/10">
                    <option value="todos">Todos los paralelos</option>
                    <option value="a">Paralelo A</option>
                    <option value="b">Paralelo B</option>
                </select>

                <div class="relative w-full sm:w-60 md:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="buscador-cursos" placeholder="Buscar curso, materia..."
                        class="w-full rounded-lg border border-gray-300 bg-gray-50/50 pl-9 pr-3 py-2 text-xs text-gray-900 placeholder-gray-400 outline-none transition focus:bg-white focus:border-brand-900 focus:ring-2 focus:ring-brand-900/10">
                </div>
            </div>
        </div>

        @php
            $coleccionBase = isset($asignacionesPorCurso) 
                ? $asignacionesPorCurso->flatten() 
                : (isset($asignaciones) ? $asignaciones : collect());

            $cursosUnicos = $coleccionBase->pluck('curso')->filter()->unique('id_cursos')->sortBy(function ($c) {
                $nivelStr = strtolower($c->nivel . ' ' . $c->nombre);
                return str_contains($nivelStr, 'prim') ? 0 : 1;
            });
        @endphp

        @if ($cursosUnicos->isNotEmpty() && auth()->user()->role->nombre !== 'Profesor')
            <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Acceso Rápido a Centralizadores</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                    @foreach ($cursosUnicos as $cursoUnico)
                        @php
                            $nivelLower = strtolower($cursoUnico->nivel . ' ' . $cursoUnico->nombre);
                            $prefix = str_contains($nivelLower, 'prim') ? 'Prim.' : 'Sec.';
                        @endphp
                        <a href="{{ route('centralizador.curso', [$trimestre, $cursoUnico]) }}"
                            class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 bg-gray-50/50 text-gray-700 text-xs font-semibold hover:bg-brand-900 hover:text-white hover:border-brand-900 transition-all group">
                            <svg class="w-3.5 h-3.5 text-brand-900 group-hover:text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                            </svg>
                            <span class="truncate">{{ $prefix }} {{ $cursoUnico->nombre }} "{{ $cursoUnico->paralelo }}"</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @php
            $gruposCursos = isset($asignacionesPorCurso) 
                ? $asignacionesPorCurso 
                : (isset($asignaciones) ? $asignaciones->groupBy('id_cursos') : collect());
        @endphp

        <div class="space-y-6" id="contenedor-cursos-general">
            @forelse ($gruposCursos as $idCurso => $asignacionesCurso)
                @php 
                    $cursoPrincipal = $asignacionesCurso->first()->curso; 
                @endphp

                <div class="curso-grupo bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden transition-all"
                    data-nivel="{{ strtolower($cursoPrincipal->nivel ?? '') }}"
                    data-paralelo="{{ strtolower($cursoPrincipal->paralelo ?? '') }}"
                    data-search-text="{{ strtolower(($cursoPrincipal->nombre ?? '') . ' ' . ($cursoPrincipal->paralelo ?? '') . ' ' . ($cursoPrincipal->nivel ?? '') . ' ' . $asignacionesCurso->pluck('materia.nombre')->implode(' ')) }}">

                    <div class="bg-brand-900 px-5 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-white">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0118 20.944 12.083 12.083 0 016 14.578a12.083 12.083 0 016.16-3.422l6.16 3.422z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-sm font-bold tracking-wide">
                                        {{ $cursoPrincipal->nombre }} "{{ $cursoPrincipal->paralelo }}"
                                    </h2>
                                    <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-white/25 text-white">
                                        {{ $cursoPrincipal->nivel }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-200 flex items-center gap-2 mt-0.5">
                                    <span><strong class="text-white">{{ $cursoPrincipal->inscripciones_count ?? 0 }}</strong> Inscritos</span>
                                    <span>•</span>
                                    <span><strong class="text-white">{{ $asignacionesCurso->count() }}</strong> Materias</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <a href="{{ route('inscripciones.show', $cursoPrincipal) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition-all border border-white/20" title="Ver Lista de Alumnos">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Estudiantes</span>
                            </a>

                            <a href="{{ route('reportes.planillas.curso', [$trimestre, $cursoPrincipal]) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition-all border border-white/20" title="Generar Reporte de este Curso">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Reporte</span>
                            </a>

                            @if (auth()->user()->role->nombre !== 'Profesor')
                                <a href="{{ route('centralizador.curso', [$trimestre, $cursoPrincipal]) }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-brand-900 hover:bg-gray-100 text-xs font-semibold transition-all shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                    </svg>
                                    <span>Centralizador</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50/50">
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                            @foreach ($asignacionesCurso as $asignacion)
                                @php
                                    $irregularidades = collect($asignacion->irregularidades ?? []);
                                    $tieneErrores = $irregularidades->isNotEmpty();
                                @endphp

                                <div class="materia-fila flex items-center justify-between p-2.5 rounded-lg bg-white border border-gray-200/90 hover:border-gray-300 transition-all gap-2"
                                    data-materia-nombre="{{ strtolower(($asignacion->materia->nombre ?? '') . ' ' . optional(optional($asignacion->docente)->persona)->nombres . ' ' . optional(optional($asignacion->docente)->persona)->apellido_p) }}">

                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-7 h-7 rounded-lg bg-brand-50 flex items-center justify-center text-brand-900 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="text-xs font-bold text-gray-900 truncate" title="{{ $asignacion->materia->nombre ?? '' }}">
                                                {{ $asignacion->materia->nombre ?? 'Sin Materia' }}
                                            </h3>
                                            @if (auth()->user()->role->nombre !== 'Profesor')
                                                <p class="text-[10px] text-gray-500 truncate">
                                                    @if(optional($asignacion->docente)->persona)
                                                        {{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellido_p }}
                                                    @else
                                                        <span class="italic text-gray-400">Sin docente</span>
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1.5 shrink-0">
                                        {{-- Badge de Auditoría / Estado de Planilla --}}
                                        @if ($tieneErrores)
                                            <button type="button" 
                                                onclick="abrirModalErrores('{{ addslashes($asignacion->materia->nombre ?? 'Materia') }}', {{ json_encode($irregularidades->values()->toArray()) }})"
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded bg-amber-50 border border-amber-200 text-amber-700 text-[10px] font-bold hover:bg-amber-100 transition-colors cursor-pointer"
                                                title="Haz clic para ver las observaciones">
                                                <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <span>{{ $irregularidades->count() }}</span>
                                            </button>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-1.5 py-1 rounded bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-medium" title="Planilla Completa y sin observaciones">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        @endif

                                        {{-- Botón de Evaluar / Planilla --}}
                                        <a href="{{ route('planilla.show', [$asignacion, $trimestre]) }}"
                                            class="inline-flex items-center gap-1 rounded bg-brand-900 hover:bg-brand-950 text-white py-1 px-2.5 font-semibold transition-colors text-[11px]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>{{ auth()->user()->role->nombre === 'Profesor' ? 'Evaluar' : 'Planilla' }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                    <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">No hay cursos asignados</h3>
                    <p class="text-xs text-gray-500 mt-0.5">No se encontraron registros de asignaciones para este periodo académico.</p>
                </div>
            @endforelse
        </div>

        <div id="no-results" class="hidden bg-white rounded-xl border border-gray-200 p-8 text-center">
            <h3 class="text-sm font-bold text-gray-900">Sin resultados coincidentes</h3>
            <p class="text-xs text-gray-500 mt-0.5">Intenta buscando con otro término o nombre de materia.</p>
        </div>

    </div>

    {{-- MODAL DE AUDITORÍA / OBSERVACIONES --}}
    <div id="modal-errores" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 w-full max-w-lg overflow-hidden transform transition-all">
            <div class="bg-amber-50 px-5 py-4 border-b border-amber-200 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Observaciones de Auditoría</h3>
                        <p class="text-xs text-amber-800 font-medium" id="modal-materia-nombre">Materia</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalErrores()" class="text-gray-400 hover:text-gray-600 rounded-lg p-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-5 max-h-[60vh] overflow-y-auto space-y-3" id="modal-lista-errores">
                {{-- Se llena dinámicamente desde JS --}}
            </div>

            <div class="bg-gray-50 px-5 py-3 border-t border-gray-200 flex justify-end">
                <button type="button" onclick="cerrarModalErrores()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-100 transition-colors">
                    Entendido
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const elBuscador = document.getElementById('buscador-cursos');
            const elNivel = document.getElementById('filtro-nivel');
            const elParalelo = document.getElementById('filtro-paralelo');
            const noResultsEl = document.getElementById('no-results');

            function aplicarFiltros() {
                const q = elBuscador?.value.toLowerCase().trim() || '';
                const nivelSel = elNivel?.value || 'todos';
                const paraleloSel = elParalelo?.value || 'todos';

                const grupos = document.querySelectorAll('.curso-grupo');
                let totalVisibles = 0;

                grupos.forEach(grupo => {
                    const nivelGrupo = (grupo.dataset.nivel || '').toLowerCase();
                    const paraleloGrupo = (grupo.dataset.paralelo || '').toLowerCase();
                    const textoGrupo = grupo.dataset.searchText || '';

                    const cumpleNivel = (nivelSel === 'todos') || nivelGrupo.includes(nivelSel);
                    const cumpleParalelo = (paraleloSel === 'todos') || (paraleloGrupo === paraleloSel);

                    if (!cumpleNivel || !cumpleParalelo) {
                        grupo.style.display = 'none';
                        return;
                    }

                    const filasMateria = grupo.querySelectorAll('.materia-fila');
                    let materiasVisiblesEnGrupo = 0;

                    if (q === '' || textoGrupo.includes(q)) {
                        grupo.style.display = '';
                        filasMateria.forEach(f => f.style.display = '');
                        totalVisibles++;
                    } else {
                        filasMateria.forEach(fila => {
                            const textoMateria = fila.dataset.materiaNombre || '';
                            if (textoMateria.includes(q)) {
                                fila.style.display = '';
                                materiasVisiblesEnGrupo++;
                            } else {
                                fila.style.display = 'none';
                            }
                        });

                        if (materiasVisiblesEnGrupo > 0) {
                            grupo.style.display = '';
                            totalVisibles++;
                        } else {
                            grupo.style.display = 'none';
                        }
                    }
                });

                if (noResultsEl) {
                    if (totalVisibles === 0) {
                        noResultsEl.classList.remove('hidden');
                    } else {
                        noResultsEl.classList.add('hidden');
                    }
                }
            }

            elBuscador?.addEventListener('input', aplicarFiltros);
            elNivel?.addEventListener('change', aplicarFiltros);
            elParalelo?.addEventListener('change', aplicarFiltros);
        });

        function abrirModalErrores(nombreMateria, listaErrores) {
            document.getElementById('modal-materia-nombre').innerText = nombreMateria;
            const contenedor = document.getElementById('modal-lista-errores');
            contenedor.innerHTML = '';

            if (listaErrores && listaErrores.length > 0) {
                listaErrores.forEach(err => {
                    let obs = typeof err === 'string' ? JSON.parse(err) : err;

                    let detalles = '';

                    if (obs.con_cero && obs.con_cero.length > 0) {
                        detalles += `
                            <div class="mt-1.5">
                                <span class="inline-block font-semibold text-amber-800 text-[11px]">Notas con valor 0:</span>
                                <div class="flex flex-wrap gap-1 mt-0.5">
                                    ${obs.con_cero.map(item => `<span class="px-1.5 py-0.5 bg-amber-100 text-amber-900 rounded text-[10px] font-medium border border-amber-200">${item}</span>`).join('')}
                                </div>
                            </div>
                        `;
                    }

                    if (obs.sin_nota && obs.sin_nota.length > 0) {
                        detalles += `
                            <div class="mt-1.5">
                                <span class="inline-block font-semibold text-red-800 text-[11px]">Faltan notas por registrar:</span>
                                <div class="flex flex-wrap gap-1 mt-0.5">
                                    ${obs.sin_nota.map(item => `<span class="px-1.5 py-0.5 bg-red-100 text-red-900 rounded text-[10px] font-medium border border-red-200">${item}</span>`).join('')}
                                </div>
                            </div>
                        `;
                    }

                    if (obs.dimensiones_incompletas && obs.dimensiones_incompletas.length > 0) {
                        detalles += `
                            <div class="mt-1.5">
                                <span class="inline-block font-semibold text-amber-800 text-[11px]">Dimensiones incompletas:</span>
                                <div class="flex flex-wrap gap-1 mt-0.5">
                                    ${obs.dimensiones_incompletas.map(item => `<span class="px-1.5 py-0.5 bg-gray-100 text-gray-800 rounded text-[10px] font-medium border border-gray-200">${item}</span>`).join('')}
                                </div>
                            </div>
                        `;
                    }

                    const tarjeta = document.createElement('div');
                    tarjeta.className = "p-3 rounded-lg bg-amber-50/70 border border-amber-200/90 shadow-2xs space-y-1";
                    tarjeta.innerHTML = `
                        <div class="flex items-center gap-2 border-b border-amber-200/60 pb-1.5">
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-bold text-xs text-gray-900">${obs.nombre || 'Estudiante #' + obs.estudiante_id}</span>
                        </div>
                        ${detalles}
                    `;
                    
                    contenedor.appendChild(tarjeta);
                });
            } else {
                contenedor.innerHTML = '<p class="text-xs text-gray-500 italic text-center py-4">No hay observaciones registradas en esta materia.</p>';
            }

            const modal = document.getElementById('modal-errores');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function cerrarModalErrores() {
            const modal = document.getElementById('modal-errores');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endpush