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
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($trimestres as $trimestre)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-bold text-gray-900">{{ $trimestre->nombres }}</td>
                            <td>{{ $trimestre->orden }}</td>
                            <td>{{ $trimestre->gestion }}</td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('trimestres.edit', $trimestre) }}" class="btn-secondary">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('trimestres.destroy', $trimestre) }}" class="inline-block"
                                    onsubmit="return confirm('¿Eliminar este trimestre?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                No hay trimestres registrados hasta el momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection