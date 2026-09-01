@extends('layouts.app')

@section('title', 'Cursos Académicos')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Cursos Académicos</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Gestione las calificaciones, listas de estudiantes y horarios de los cursos asignados para el periodo
                    actual.
                </p>
            </div>

            <div class="relative w-full sm:w-64">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="buscador-cursos" placeholder="Buscar curso..."
                    class="w-full rounded-lg border border-gray-300 bg-white pl-9 pr-4 py-2 text-sm outline-none focus:border-brand-800 focus:ring-2 focus:ring-brand-800/10">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($asignaciones as $asignacion)
                @php $curso = $asignacion->curso; @endphp

                <div class="curso-card relative bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
                    data-nombre="{{ strtolower($curso->nombre . ' ' . $curso->paralelo . ' ' . $asignacion->materia->nombre) }}">

                    <div class="h-1 bg-brand-900"></div>

                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <span
                                class="text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded-full bg-red-100 text-brand-900">
                                {{ $curso->nivel }}
                            </span>
                            <span class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-brand-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0118 20.944 12.083 12.083 0 016 14.578a12.083 12.083 0 016.16-3.422l6.16 3.422z" />
                                </svg>
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-gray-900">
                            {{ $curso->nombre }} '{{ $curso->paralelo }}' {{ $asignacion->materia->nombre }}
                        </h3>

                        @if (auth()->user()->role->nombre !== 'Profesor')
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Prof. guía: <span class="font-medium text-gray-700">{{ $asignacion->docente->persona->nombres }}
                                    {{ $asignacion->docente->persona->apellido_p }}</span>
                            </p>
                        @endif

                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Estudiantes: <span class="font-medium text-gray-700">{{ $curso->inscripciones_count ?? 0 }}
                                inscritos</span>
                        </p>

                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <a href="{{ route('planilla.show', [$asignacion, $trimestre]) }}"
                                class="flex flex-col items-center gap-1 rounded-lg bg-brand-900 hover:bg-brand-950 text-white py-2.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                </svg>
                                <span class="text-[10px] font-semibold uppercase">
                                    {{ auth()->user()->role->nombre === 'Profesor' ? 'Evaluar' : 'Planilla' }}
                                </span>
                            </a>

                            <a href="{{ route('inscripciones.show', $curso) }}"
                                class="flex flex-col items-center gap-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="text-[10px] font-semibold uppercase">Estudiantes</span>
                            </a>

                            <a href=""
                                class="flex flex-col items-center gap-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-[10px] font-semibold uppercase">Horarios</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('buscador-cursos')?.addEventListener('input', function (e) {
            const q = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.curso-card').forEach(card => {
                card.style.display = card.dataset.nombre.includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush