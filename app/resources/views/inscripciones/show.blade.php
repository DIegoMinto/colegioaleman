@extends('layouts.app')

@section('title', 'Estudiantes inscritos')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">
                    Estudiantes en {{ $curso->nombre }} "{{ $curso->paralelo }}"
                </h1>
                <p class="text-sm text-gray-500 mt-1">Listado de inscripciones activas para este curso.</p>
            </div>
            <a href="{{ route('inscripciones.create', $curso) }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Inscribir más estudiantes
            </a>
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
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Gestión</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($inscripciones as $inscripcion)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-bold text-gray-900">
                                {{ $inscripcion->estudiante->persona->nombres }}
                                {{ $inscripcion->estudiante->persona->apellido_p }}
                            </td>
                            <td>{{ $inscripcion->estudiante->persona->ci }}</td>
                            <td>{{ $inscripcion->gestion }}</td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('inscripciones.destroy', $inscripcion) }}"
                                    class="inline-block" onsubmit="return confirm('¿Quitar esta inscripción?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">Quitar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                Aún no hay estudiantes inscritos en este curso.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection