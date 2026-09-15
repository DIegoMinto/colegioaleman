@extends('layouts.app')

@section('title', 'Asignaciones')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Asignaciones</h1>
                <p class="text-sm text-gray-500 mt-1">Docentes asignados a cursos y materias por gestión.</p>
            </div>
            <div>
                <a href="{{ route('asignaciones.create') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Asignación
                </a>
            </div>
        </div>

        @if (session('exito'))
            <div
                class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('exito') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtrar Asignaciones
                </h3>
                <button type="button" id="btn-limpiar-filtros" class="text-xs text-brand-800 font-semibold hover:underline">
                    Limpiar Filtros
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Docente</label>
                    <select id="filtro-docente"
                        class="w-full text-xs rounded-lg border-gray-300 focus:border-brand-800 focus:ring-brand-800">
                        <option value="">Todos los docentes</option>
                        @foreach ($docentes as $doc)
                            @php
                                $p = $doc->persona;
                                $nombreDoc = trim("{$p->apellido_p} " . ($p->apellido_m ? "{$p->apellido_m} " : '') . $p->nombres);
                            @endphp
                            <option value="{{ $doc->id_docente }}">{{ $nombreDoc }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nivel</label>
                    <select id="filtro-nivel"
                        class="w-full text-xs rounded-lg border-gray-300 focus:border-brand-800 focus:ring-brand-800">
                        <option value="">Todos los niveles</option>
                        @foreach ($niveles as $nivel)
                            <option value="{{ strtolower($nivel) }}">{{ ucfirst($nivel) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Curso</label>
                    <select id="filtro-curso"
                        class="w-full text-xs rounded-lg border-gray-300 focus:border-brand-800 focus:ring-brand-800">
                        <option value="">Todos los cursos</option>
                        @foreach ($cursos as $curso)
                            <option value="{{ strtolower($curso) }}">{{ $curso }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Paralelo</label>
                    <select id="filtro-paralelo"
                        class="w-full text-xs rounded-lg border-gray-300 focus:border-brand-800 focus:ring-brand-800">
                        <option value="">Todos los paralelos</option>
                        @foreach ($paralelos as $paralelo)
                            <option value="{{ strtolower($paralelo) }}">Paralelo "{{ $paralelo }}"</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table class="table-custom" id="tabla-asignaciones">
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Nivel / Curso</th>
                        <th>Materia</th>
                        <th>Gestión</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($asignaciones as $asignacion)
                        @php
                            $persona = $asignacion->docente->persona;
                            $nombreDocente = trim("{$persona->apellido_p} " . ($persona->apellido_m ? "{$persona->apellido_m} " : '') . $persona->nombres);
                        @endphp
                        <tr class="fila-asignacion hover:bg-gray-50 transition-colors"
                            data-docente="{{ $asignacion->id_docente }}"
                            data-nivel="{{ strtolower($asignacion->curso->nivel ?? '') }}"
                            data-curso="{{ strtolower($asignacion->curso->nombre ?? '') }}"
                            data-paralelo="{{ strtolower($asignacion->curso->paralelo ?? '') }}">

                            <td class="font-bold text-gray-900">
                                {{ $nombreDocente }}
                            </td>
                            <td>
                                <span class="font-medium text-gray-800">{{ $asignacion->curso->nombre }}
                                    "{{ $asignacion->curso->paralelo }}"</span>
                                @if(!empty($asignacion->curso->nivel))
                                    <span class="block text-xs text-gray-500 uppercase">{{ $asignacion->curso->nivel }}</span>
                                @endif
                            </td>
                            <td>{{ $asignacion->materia->nombre }}</td>
                            <td>{{ $asignacion->gestion }}</td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('asignaciones.edit', $asignacion) }}" class="btn-secondary">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </a>

                                <button type="button" class="btn-danger-sm btn-eliminar" data-title="¿Eliminar asignación?"
                                    data-message="¿Seguro que quieres eliminar la asignación de {{ $nombreDocente }} en {{ $asignacion->curso->nombre }} &quot;{{ $asignacion->curso->paralelo }}&quot; ({{ $asignacion->materia->nombre }})?"
                                    data-url="{{ route('asignaciones.destroy', $asignacion) }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="sin-registros">
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                No hay asignaciones registradas hasta el momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fDocente = document.getElementById('filtro-docente');
            const fNivel = document.getElementById('filtro-nivel');
            const fCurso = document.getElementById('filtro-curso');
            const fParalelo = document.getElementById('filtro-paralelo');
            const btnLimpiar = document.getElementById('btn-limpiar-filtros');
            const filas = document.querySelectorAll('.fila-asignacion');

            function aplicarFiltros() {
                const vDocente = fDocente.value;
                const vNivel = fNivel.value;
                const vCurso = fCurso.value;
                const vParalelo = fParalelo.value;

                filas.forEach(row => {
                    const docOk = !vDocente || row.dataset.docente === vDocente;
                    const nivelOk = !vNivel || row.dataset.nivel === vNivel;
                    const cursoOk = !vCurso || row.dataset.curso === vCurso;
                    const paraleloOk = !vParalelo || row.dataset.paralelo === vParalelo;

                    row.style.display = (docOk && nivelOk && cursoOk && paraleloOk) ? '' : 'none';
                });
            }

            [fDocente, fNivel, fCurso, fParalelo].forEach(select => {
                if (select) select.addEventListener('change', aplicarFiltros);
            });

            if (btnLimpiar) {
                btnLimpiar.addEventListener('click', function () {
                    fDocente.value = '';
                    fNivel.value = '';
                    fCurso.value = '';
                    fParalelo.value = '';
                    aplicarFiltros();
                });
            }
        });
    </script>
@endpush