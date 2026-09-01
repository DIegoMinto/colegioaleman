@extends('layouts.app')

@section('title', 'Cursos')

@section('content')
    <div class="space-y-6">

        <!-- Encabezado de Sección -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Gestión de Cursos</h1>
                <p class="text-sm text-gray-500 mt-0.5">Administra los cursos, niveles y paralelos registrados en la
                    institución.</p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('cursos.create') }}" class="btn-primary !w-auto">
                    <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Curso
                </a>
            </div>
        </div>

        <!-- Alerta de Éxito -->
        @if (session('exito'))
            <div
                class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('exito') }}</span>
            </div>
        @endif

        <!-- Tabla de Cursos con Cabecera Guindo -->
        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Nivel</th>
                        <th>Paralelo</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cursos as $curso)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="font-bold text-gray-900">
                                {{ $curso->nombre }}
                            </td>
                            <td>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $curso->nivel }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-900 border border-brand-200">
                                    Paralelo {{ $curso->paralelo }}
                                </span>
                            </td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('cursos.edit', $curso) }}" class="btn-secondary">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('cursos.destroy', $curso) }}" class="inline-block"
                                    onsubmit="return confirm('¿Eliminar este curso?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-500">
                                No hay cursos registrados hasta el momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection