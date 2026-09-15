@extends('layouts.app')

@section('title', 'Mis Boletines')

@section('content')
    <div class="space-y-6">

        <div class="pb-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Mis Boletines</h1>
            <p class="text-sm text-gray-500 mt-1">
                Consulta y descarga tus libretas escolares por gestión y trimestre.
            </p>
        </div>

        @forelse ($inscripciones as $gestion => $inscripcionesGestion)
            @php $inscripcion = $inscripcionesGestion->first(); @endphp

            <div class="rounded-xl border border-gray-200 bg-white p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-brand-900">Gestión {{ $gestion }}</h2>
                        <p class="text-xs text-gray-500">
                            Curso: {{ $inscripcion->curso->nombre }} "{{ $inscripcion->curso->paralelo }}"
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('boletines.show', ['inscripcion' => $inscripcion->id_inscripciones, 'trimestre' => 1]) }}"
                        target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-sky-200 bg-sky-50 text-sky-800 font-medium hover:bg-sky-100 transition-all text-sm">
                        <span>Primer Trimestre (1°)</span>
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('boletines.show', ['inscripcion' => $inscripcion->id_inscripciones, 'trimestre' => 2]) }}"
                        target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-800 font-medium hover:bg-indigo-100 transition-all text-sm">
                        <span>Segundo Trimestre (2°)</span>
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('boletines.show', ['inscripcion' => $inscripcion->id_inscripciones, 'trimestre' => 3]) }}"
                        target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-purple-200 bg-purple-50 text-purple-800 font-medium hover:bg-purple-100 transition-all text-sm">
                        <span>Tercer Trimestre (3°)</span>
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-500 text-sm">
                No tienes inscripciones registradas en el sistema.
            </div>
        @endforelse

    </div>
@endsection