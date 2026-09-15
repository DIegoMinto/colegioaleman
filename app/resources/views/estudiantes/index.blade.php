@extends('layouts.app')

@section('title', 'Estudiantes')

@section('content')
    <div class="space-y-6">

        <x-page-header title="Estudiantes" subtitle="Estudiantes registrados en el sistema."
            :action-route="route('registro.estudiante.create')" action-label="Registrar Estudiante" />

        <div class="flex justify-end">
            <a href="{{ route('estudiantes.importar.form') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Importar Lista (PDF del SIE)
            </a>
            <div class="flex gap-2">
                <a href="{{ route('estudiantes.exportar.pdf') }}" target="_blank" class="btn-secondary ml-2">Exportar PDF</a>
                <a href="{{ route('estudiantes.exportar.csv') }}" class="btn-secondary">Exportar CSV</a>
            </div>
        </div>

        @include('components.alerts')

        <div class="table-container overflow-x-auto">
            <table class="table-custom text-sm">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Fecha Nac.</th>
                        <th>Sexo</th>
                        <th>Departamento</th>
                        <th>Cuenta</th>
                        <th>Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($estudiantes as $index => $estudiante)
                        @php 
                            $p = $estudiante->persona;
                            $u = $p->usuario; 
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Columna N° correlativa según la página actual --}}
                            <td class="text-center font-semibold text-gray-500 whitespace-nowrap">
                                {{ $estudiantes->firstItem() + $index }}
                            </td>
                            <td class="whitespace-nowrap font-bold text-gray-900">
                                {{ $p->nombres }} {{ $p->apellido_p }} {{ $p->apellido_m }}
                            </td>
                            <td class="whitespace-nowrap text-gray-600">{{ $p->ci }}</td>
                            <td class="whitespace-nowrap text-gray-600">{{ $p->fecha_nacimiento ? $p->fecha_nacimiento->format('d/m/Y') : '-' }}</td>
                            <td class="whitespace-nowrap text-gray-600">
                                {{ $p->sexo === 'M' ? 'Masculino' : ($p->sexo === 'F' ? 'Femenino' : '-') }}
                            </td>
                            <td class="whitespace-nowrap text-gray-600">{{ $p->departamento_residencia ?? '-' }}</td>
                            <td class="whitespace-nowrap">
                                <div class="text-gray-900">{{ $u->email }}</div>
                                <div class="text-xs text-gray-500">{{ $u->user }}</div>
                            </td>
                            <td class="whitespace-nowrap">
                                <x-status-badge :active="$u->activo" />
                            </td>
                            <td class="whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('estudiantes.edit', $estudiante) }}" class="btn-secondary">Editar</a>
                                <form method="POST" action="{{ route('estudiantes.toggle', $estudiante) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $u->activo ? 'btn-danger-sm' : 'btn-secondary' }}">
                                        {{ $u->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-500">
                                No se encontraron estudiantes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $estudiantes->links('components.pagination') }}

    </div>
@endsection