@extends('layouts.app')

@section('title', 'Inscribir estudiantes')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Inscribir Estudiantes</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $curso->nombre }} "{{ $curso->paralelo }}" &middot; Gestión {{ $gestion }}
                </p>
            </div>
            <a href="{{ route('inscripciones.index') }}" class="btn-secondary">
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
                No hay estudiantes disponibles para inscribir (todos ya están inscritos en este curso y gestión).
            </div>
        @else
            <form method="POST" action="{{ route('inscripciones.store', $curso) }}" class="space-y-5">
                @csrf
                <input type="hidden" name="gestion" value="{{ $gestion }}">

                <div class="table-container">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th class="w-10"></th>
                                <th>Nombre Completo</th>
                                <th>CI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($estudiantesDisponibles as $estudiante)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td>
                                        <input type="checkbox" name="estudiantes[]" value="{{ $estudiante->id_estudiantes }}"
                                            id="est_{{ $estudiante->id_estudiantes }}"
                                            class="w-4 h-4 rounded border-gray-300 text-brand-900 focus:ring-brand-800/40">
                                    </td>
                                    <td class="font-medium text-gray-900">
                                        <label for="est_{{ $estudiante->id_estudiantes }}" class="cursor-pointer">
                                            {{ $estudiante->persona->nombres }} {{ $estudiante->persona->apellido_p }}
                                            {{ $estudiante->persona->apellido_m }}
                                        </label>
                                    </td>
                                    <td class="text-gray-600">{{ $estudiante->persona->ci }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('inscripciones.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">
                        Inscribir Seleccionados
                    </button>
                </div>
            </form>
        @endif

    </div>
@endsection