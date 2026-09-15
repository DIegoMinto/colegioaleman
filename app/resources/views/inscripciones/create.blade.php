@extends('layouts.app')

@section('title', 'Inscribir estudiantes')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Inscribir Estudiantes</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $curso->nombre }} "{{ $curso->paralelo }}" &middot; Gestión {{ $gestion }}
                </p>
            </div>
            <a href="{{ route('inscripciones.show', ['curso' => $curso, 'gestion' => $gestion]) }}"
                class="btn-secondary self-start sm:self-auto">
                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 space-y-2">
                <div class="flex items-center gap-2 font-semibold text-red-800">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Por favor corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside pl-2 space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($estudiantesDisponibles->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-10 text-center text-gray-500">
                No hay estudiantes disponibles para inscribir en la gestión {{ $gestion }} (todos los registrados ya pertenecen
                a este u otro curso).
            </div>
        @else
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                <div class="relative w-full sm:w-80">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="buscador-disponibles" placeholder="Buscar por nombre o CI..."
                        class="w-full rounded-lg border border-gray-300 bg-white pl-9 pr-4 py-2 text-sm outline-none focus:border-brand-800 focus:ring-2 focus:ring-brand-800/10">
                </div>

                <div class="text-xs font-semibold text-gray-500 self-end sm:self-center">
                    Seleccionados: <span id="contador-seleccionados" class="text-brand-900 font-bold">0</span>
                </div>
            </div>

            <form method="POST" action="{{ route('inscripciones.store', $curso) }}" class="space-y-5">
                @csrf
                <input type="hidden" name="gestion" value="{{ $gestion }}">

                <div class="table-container">
                    <table class="table-custom" id="tabla-disponibles">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">
                                    <input type="checkbox" id="check-todos" title="Seleccionar todos los visibles"
                                        class="w-4 h-4 rounded border-gray-300 text-brand-900 focus:ring-brand-800/40 cursor-pointer">
                                </th>
                                <th>Nombre Completo</th>
                                <th>CI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($estudiantesDisponibles as $estudiante)
                                @php
                                    $nombreCompleto = trim($estudiante->persona->nombres . ' ' . $estudiante->persona->apellido_p . ' ' . ($estudiante->persona->apellido_m ?? ''));
                                    $ci = $estudiante->persona->ci;
                                @endphp
                                <tr class="fila-estudiante hover:bg-gray-50 transition-colors"
                                    data-busqueda="{{ strtolower($nombreCompleto . ' ' . $ci) }}">
                                    <td class="text-center">
                                        <input type="checkbox" name="estudiantes[]" value="{{ $estudiante->id_estudiantes }}"
                                            id="est_{{ $estudiante->id_estudiantes }}"
                                            class="check-estudiante w-4 h-4 rounded border-gray-300 text-brand-900 focus:ring-brand-800/40 cursor-pointer">
                                    </td>
                                    <td class="font-medium text-gray-900">
                                        <label for="est_{{ $estudiante->id_estudiantes }}" class="cursor-pointer block w-full">
                                            {{ $nombreCompleto }}
                                        </label>
                                    </td>
                                    <td class="text-gray-600">{{ $ci }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('inscripciones.show', ['curso' => $curso, 'gestion' => $gestion]) }}"
                        class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">
                        Inscribir Seleccionados
                    </button>
                </div>
            </form>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buscador = document.getElementById('buscador-disponibles');
            const checkTodos = document.getElementById('check-todos');
            const contador = document.getElementById('contador-seleccionados');

            const normalizar = (texto) => {
                return (texto || '')
                    .toLowerCase()
                    .normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, "")
                    .trim();
            };

            function actualizarContador() {
                const checksMarcados = document.querySelectorAll('.check-estudiante:checked').length;
                if (contador) contador.textContent = checksMarcados;
            }

            buscador?.addEventListener('input', function (e) {
                const query = normalizar(e.target.value);

                document.querySelectorAll('#tabla-disponibles .fila-estudiante').forEach(row => {
                    const textoFila = normalizar(row.dataset.busqueda);
                    row.style.display = textoFila.includes(query) ? '' : 'none';
                });
            });

            checkTodos?.addEventListener('change', function () {
                const filasVisibles = document.querySelectorAll('#tabla-disponibles .fila-estudiante:not([style*="display: none"])');
                filasVisibles.forEach(row => {
                    const chk = row.querySelector('.check-estudiante');
                    if (chk) chk.checked = checkTodos.checked;
                });
                actualizarContador();
            });

            document.querySelectorAll('.check-estudiante').forEach(chk => {
                chk.addEventListener('change', actualizarContador);
            });
        });
    </script>
@endpush