@extends('layouts.app')

@section('title', 'Previsualizar importación')

@section('content')
    <div class="space-y-6">

        <div class="pb-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Previsualizar Importación</h1>
            <p class="text-sm text-gray-500 mt-1">{{ count($preview) }} filas detectadas en el PDF</p>
        </div>

        <div class="flex gap-4 text-xs font-semibold">
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                Nuevos: {{ collect($preview)->where('estado', 'nuevo')->count() }}
            </span>
            <span class="px-3 py-1 rounded-full bg-sky-50 text-sky-800 border border-sky-200">
                Ya existentes: {{ collect($preview)->where('estado', 'existente')->count() }}
            </span>
            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                Omitidos (retirados): {{ collect($preview)->where('estado', 'omitido')->count() }}
            </span>
        </div>

        <div class="table-container overflow-x-auto">
            <table class="table-custom text-sm">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>RUDE</th>
                        <th>Carnet</th>
                        <th>Nombre Completo</th>
                        <th>Sexo</th>
                        <th>Fecha Nac.</th>
                        <th>Departamento</th>
                        <th>Correo Institucional</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($preview as $fila)
                        <tr class="hover:bg-gray-50 transition-colors {{ $fila['estado'] === 'omitido' ? 'opacity-50' : '' }}">
                            <td>{{ $fila['numero_fila'] }}</td>
                            <td class="whitespace-nowrap text-gray-600">{{ $fila['codigo_rude'] }}</td>
                            <td class="whitespace-nowrap text-gray-600">{{ $fila['carnet'] }}</td>
                            <td class="whitespace-nowrap font-medium text-gray-900">{{ $fila['nombre_completo'] }}</td>
                            <td>{{ $fila['genero'] }}</td>
                            <td
                                class="whitespace-nowrap {{ !$fila['fecha_valida'] ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                {{ $fila['fecha_nacimiento'] ?? 'No se pudo leer' }}
                            </td>
                            <td class="whitespace-nowrap text-gray-600">{{ $fila['departamento'] }}</td>
                            <td class="whitespace-nowrap text-gray-600">{{ $fila['email_proyectado'] ?? '—' }}</td>
                            <td class="whitespace-nowrap">
                                @if ($fila['estado'] === 'nuevo')
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-semibold">Nuevo</span>
                                @elseif ($fila['estado'] === 'existente')
                                    <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-800 text-xs font-semibold">Ya
                                        existe</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">Omitido</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('estudiantes.importar.form') }}" class="btn-secondary">Subir otro archivo</a>
            <form method="POST" action="{{ route('estudiantes.importar.confirmar') }}">
                @csrf
                <button type="submit" class="btn-primary">Confirmar Importación</button>
            </form>
        </div>

    </div>
@endsection