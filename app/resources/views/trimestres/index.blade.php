@extends('layouts.app')

@section('title', 'Trimestres')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Trimestres</h1>
                <p class="text-sm text-gray-500 mt-1">Gestión de los trimestres por gestión académica.</p>
            </div>
            <div>
                <a href="{{ route('trimestres.create') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Trimestre
                </a>
            </div>
        </div>

        @include('components.alerts')

        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Orden</th>
                        <th>Gestión</th>
                        <th>Periodo</th>
                        <th>Estado Edición</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($trimestres as $trimestre)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-bold text-gray-900">{{ $trimestre->nombres }}</td>
                            <td>{{ $trimestre->orden }}</td>
                            <td>{{ $trimestre->gestion }}</td>
                            <td class="text-sm text-gray-600">
                                @if ($trimestre->fecha_inicio && $trimestre->fecha_fin)
                                    {{ $trimestre->fecha_inicio->format('d/m/Y') }} - {{ $trimestre->fecha_fin->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-400">Sin definir</span>
                                @endif
                            </td>
                            <td>
                                @if ($trimestre->activo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        Abierto (Habilitado)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Cerrado (Bloqueado)
                                    </span>
                                @endif
                            </td>
                            <td class="text-right space-x-2">
                                <form method="POST" action="{{ route('trimestres.toggle-estado', $trimestre) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    @if ($trimestre->activo)
                                        <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-md bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition-colors" title="Cerrar trimestre para que docentes no editen notas">
                                            Cerrar
                                        </button>
                                    @else
                                        <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors" title="Abrir trimestre para permitir edición de notas">
                                            Abrir
                                        </button>
                                    @endif
                                </form>

                                <a href="{{ route('trimestres.edit', $trimestre) }}" class="btn-secondary">
                                    Editar
                                </a>

                                <button type="button" 
                                    class="btn-danger-sm btn-eliminar"
                                    data-title="¿Eliminar trimestre?"
                                    data-message="¿Estás seguro de que deseas eliminar {{ $trimestre->nombres }}?"
                                    data-url="{{ route('trimestres.destroy', $trimestre) }}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                No hay trimestres registrados hasta el momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection