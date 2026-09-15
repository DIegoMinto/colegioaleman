@extends('layouts.app')

@section('title', 'Estudiantes inscritos')

@section('content')
    <div x-data="{ 
                                open: false, 
                                estudiante: '', 
                                idInscripcion: '',
                                baseUrl: '{{ url('/boletines') }}',

                                abrirModal(nombre, id) {
                                    this.estudiante = nombre;
                                    this.idInscripcion = id;
                                    this.open = true;
                                }
                            }" @abrir-modal-boletin.window="abrirModal($event.detail.nombre, $event.detail.id)"
        @keydown.escape.window="open = false" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
        aria-modal="true">

        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="open = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100 p-6 space-y-4">

                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">
                        Boletín: <span class="text-brand-800" x-text="estudiante"></span>
                    </h3>
                    <button type="button" @click="open = false"
                        class="text-gray-400 hover:text-gray-600 text-xl font-bold p-1 leading-none">
                        &times;
                    </button>
                </div>

                <p class="text-sm text-gray-600">
                    Selecciona el trimestre del cual deseas generar o visualizar el boletín de calificaciones:
                </p>

                <div class="grid grid-cols-1 gap-3 py-2">
                    <a :href="`${baseUrl}/${idInscripcion}/1`" target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-sky-200 bg-sky-50 text-sky-800 font-medium hover:bg-sky-100 transition-all">
                        <span>Primer Trimestre (1°)</span>
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a :href="`${baseUrl}/${idInscripcion}/2`" target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-800 font-medium hover:bg-indigo-100 transition-all">
                        <span>Segundo Trimestre (2°)</span>
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a :href="`${baseUrl}/${idInscripcion}/3`" target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-purple-200 bg-purple-50 text-purple-800 font-medium hover:bg-purple-100 transition-all">
                        <span>Tercer Trimestre (3°)</span>
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="pt-3 border-t border-gray-100 text-right">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm font-semibold rounded-lg transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">
                    Estudiantes en {{ $curso->nombre }} "{{ $curso->paralelo }}"
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Inscripciones correspondientes a la <span class="font-semibold text-brand-800">Gestión
                        {{ $gestionSeleccionada }}</span>.
                </p>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                <div x-data="{ openImpresion: false }" class="relative" @keydown.escape.window="openImpresion = false">
                    <button type="button" @click="openImpresion = !openImpresion"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-900 hover:bg-brand-950 text-white px-4 py-2 text-xs font-bold uppercase tracking-wide shrink-0 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimir boletines del curso
                    </button>

                    <div x-show="openImpresion" @click.outside="openImpresion = false" x-cloak
                        class="absolute right-0 mt-2 w-56 rounded-lg border border-gray-200 bg-white shadow-lg z-20 overflow-hidden">
                        <a href="{{ route('boletines.curso.pdf', [$curso, $gestionSeleccionada, 1]) }}" target="_blank"
                            class="block px-4 py-2 text-sm text-sky-800 hover:bg-sky-50 transition-colors">
                            1° Trimestre — Todo el curso
                        </a>
                        <a href="{{ route('boletines.curso.pdf', [$curso, $gestionSeleccionada, 2]) }}" target="_blank"
                            class="block px-4 py-2 text-sm text-indigo-800 hover:bg-indigo-50 transition-colors">
                            2° Trimestre — Todo el curso
                        </a>
                        <a href="{{ route('boletines.curso.pdf', [$curso, $gestionSeleccionada, 3]) }}" target="_blank"
                            class="block px-4 py-2 text-sm text-purple-800 hover:bg-purple-50 transition-colors">
                            3° Trimestre — Todo el curso
                        </a>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="buscador-inscritos" placeholder="Buscar por nombre o CI..."
                            class="w-full rounded-lg border border-gray-300 bg-white pl-9 pr-4 py-2 text-sm outline-none focus:border-brand-800 focus:ring-2 focus:ring-brand-800/10 transition-all">
                    </div>

                    @if ($gestionSeleccionada == date('Y'))
                        <a href="{{ route('inscripciones.create', $curso) }}"
                            class="btn-primary flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Inscribir más estudiantes
                        </a>
                    @else
                        <span
                            class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-gray-600 bg-gray-100 rounded-lg border border-gray-200 shrink-0">
                            Gestión Histórica (Solo Lectura)
                        </span>
                    @endif
                </div>
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

        <div class="table-container">
            <table class="table-custom" id="tabla-inscritos">
                <thead>
                    <tr>
                        <th class="w-12 text-center">N°</th>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Gestión</th>
                        <th class="text-center">Boletines Trimestrales</th>
                        @if ($gestionSeleccionada == date('Y'))
                            <th class="text-right">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($inscripciones as $inscripcion)
                        @php
                            $persona = $inscripcion->estudiante->persona;
                            $nombreCompleto = trim("{$persona->apellido_p} " . ($persona->apellido_m ? "{$persona->apellido_m} " : '') . $persona->nombres);
                            $ci = $persona->ci;
                        @endphp
                        <tr class="fila-estudiante hover:bg-gray-50 transition-colors"
                            data-busqueda="{{ strtolower($nombreCompleto . ' ' . $ci) }}">
                            <td class="text-center font-semibold text-gray-500">{{ $loop->iteration }}</td>
                            <td class="font-bold text-gray-900">{{ $nombreCompleto }}</td>
                            <td>{{ $ci }}</td>
                            <td>{{ $inscripcion->gestion }}</td>

                            <td class="text-center py-3">
                                <div class="inline-flex rounded-md shadow-sm gap-1" role="group">
                                    <a href="{{ route('boletines.show', ['inscripcion' => $inscripcion->id_inscripciones, 'trimestre' => 1]) }}"
                                        target="_blank"
                                        class="px-2 py-1 text-xs font-semibold text-sky-700 bg-sky-50 border border-sky-200 rounded-l hover:bg-sky-100 transition-colors">
                                        1° Trim.
                                    </a>
                                    <a href="{{ route('boletines.show', ['inscripcion' => $inscripcion->id_inscripciones, 'trimestre' => 2]) }}"
                                        target="_blank"
                                        class="px-2 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition-colors">
                                        2° Trim.
                                    </a>
                                    <a href="{{ route('boletines.show', ['inscripcion' => $inscripcion->id_inscripciones, 'trimestre' => 3]) }}"
                                        target="_blank"
                                        class="px-2 py-1 text-xs font-semibold text-purple-700 bg-purple-50 border border-purple-200 rounded-r hover:bg-purple-100 transition-colors">
                                        3° Trim.
                                    </a>
                                </div>
                            </td>

                            @if ($gestionSeleccionada == date('Y'))
                                <td class="text-right">
                                    <button type="button" class="btn-danger-sm" @click="$dispatch('abrir-modal', { 
                                                                                                title: '¿Quitar inscripción?', 
                                                                                                message: 'Estás a punto de desinscribir a {{ $nombreCompleto }} de este curso.', 
                                                                                                url: '{{ route('inscripciones.destroy', $inscripcion) }}' 
                                                                                            })">
                                        Quitar
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $gestionSeleccionada == date('Y') ? 6 : 5 }}"
                                class="text-center py-10 text-gray-500">
                                No hay estudiantes inscritos en este curso para la gestión {{ $gestionSeleccionada }}.
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
            const buscador = document.getElementById('buscador-inscritos');
            const filas = document.querySelectorAll('#tabla-inscritos .fila-estudiante');

            if (buscador) {
                const normalizar = (texto) => (texto || '').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();

                buscador.addEventListener('input', function (e) {
                    const query = normalizar(e.target.value);
                    filas.forEach(row => {
                        const textoFila = normalizar(row.dataset.busqueda);
                        row.style.display = textoFila.includes(query) ? '' : 'none';
                    });
                });
            }
        });
    </script>
@endpush