@extends('layouts.app')

@section('title', 'Docentes')

@section('content')
    <div class="space-y-6">

        <x-page-header title="Docentes" subtitle="Personal docente registrado en el sistema."
            :action-route="route('registro.docente.create')" action-label="Registrar Docente" />

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
        <div class="flex gap-2 justify-end">
                <a href="{{ route('docentes.exportar.pdf') }}" target="_blank" class="btn-secondary ml-2">Exportar PDF</a>
                <a href="{{ route('docentes.exportar.csv') }}" class="btn-secondary">Exportar CSV</a>
        </div>
        <div class="table-container overflow-x-auto">
            <table class="table-custom text-sm">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Fecha Nac.</th>
                        <th>Contacto</th>
                        <th>Departamento</th>
                        <th>Cuenta</th>
                        <th>Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($docentes as $index => $docente)
                        @php $p = $docente->persona;
                        $u = $p->usuario; 
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="text-center font-semibold text-gray-500 whitespace-nowrap">
                                {{ $docentes->firstItem() + $index }}
                            </td>
                            <td class="whitespace-nowrap font-bold text-gray-900">
                                {{ $p->nombres }} {{ $p->apellido_p }} {{ $p->apellido_m }}
                            </td>
                            <td class="whitespace-nowrap text-gray-600">{{ $p->ci }}</td>
                            <td class="whitespace-nowrap text-gray-600">{{ $p->fecha_nacimiento->format('d/m/Y') }}</td>
                            <td class="whitespace-nowrap text-gray-600">{{ $p->celular ?? '-' }}</td>
                            <td class="whitespace-nowrap text-gray-600">{{ $p->departamento_residencia ?? '-' }}</td>
                            <td class="whitespace-nowrap">
                                <div class="text-gray-900">{{ $u->email }}</div>
                                <div class="text-xs text-gray-500">{{ $u->user }}</div>
                            </td>
                            <td class="whitespace-nowrap">
                                <x-status-badge :active="$u->activo" />
                            </td>
                            <td class="whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('docentes.edit', $docente) }}" class="btn-secondary">Editar</a>
                                <form method="POST" action="{{ route('docentes.toggle', $docente) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $u->activo ? 'btn-danger-sm' : 'btn-secondary' }}">
                                        {{ $u->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $docentes->links('components.pagination') }}

    </div>
@endsection