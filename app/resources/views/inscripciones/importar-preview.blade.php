@extends('layouts.app')

@section('title', 'Previsualizar inscripción masiva')

@section('content')
    <div class="space-y-6">

        <div class="pb-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Previsualizar Inscripción</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $curso->nombre }} "{{ $curso->paralelo }}" &middot; Gestión {{ $gestion }}
                &middot; {{ count($preview) }} filas detectadas
            </p>
        </div>

        <div class="flex flex-wrap gap-4 text-xs font-semibold">
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                Por inscribir: {{ collect($preview)->where('estado', 'por_inscribir')->count() }}
            </span>
            <span class="px-3 py-1 rounded-full bg-sky-50 text-sky-800 border border-sky-200">
                Ya inscritos: {{ collect($preview)->where('estado', 'ya_inscrito')->count() }}
            </span>
            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 border border-red-200">
                No existen: {{ collect($preview)->where('estado', 'no_existe')->count() }}
            </span>
            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                Omitidos (retirados): {{ collect($preview)->where('estado', 'omitido')->count() }}
            </span>
        </div>

        @if (collect($preview)->where('estado', 'no_existe')->count() > 0)
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                Hay estudiantes en el PDF que todavía no existen en el sistema. Impórtalos primero desde
                <a href="{{ route('estudiantes.importar.form') }}" class="underline font-semibold">Estudiantes &rarr; Importar Lista</a>,
                y luego vuelve a intentar la inscripción.
            </div>
        @endif

        <div class="table-container overflow-x-auto">
            <table class="table-custom text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Carnet</th>
                        <th>Nombre Completo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($preview as $fila)
                        <tr class="hover:bg-gray-50 transition-colors {{ $fila['estado'] === 'omitido' ? 'opacity-50' : '' }}">
                            <td>{{ $fila['numero_fila'] }}</td>
                            <td class="whitespace-nowrap text-gray-600">{{ $fila['carnet'] }}</td>
                            <td class="whitespace-nowrap font-medium text-gray-900">{{ $fila['nombre_completo'] }}</td>
                            <td class="whitespace-nowrap">
                                @if ($fila['estado'] === 'por_inscribir')
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-semibold">Por inscribir</span>
                                @elseif ($fila['estado'] === 'ya_inscrito')
                                    <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-800 text-xs font-semibold">Ya inscrito</span>
                                @elseif ($fila['estado'] === 'no_existe')
                                    <span class="px-2 py-0.5 rounded-full bg-red-50 text-red-700 text-xs font-semibold">No existe</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">Omitido</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('inscripciones.importar.form', $curso) }}" class="btn-secondary">Subir otro archivo</a>
            <form method="POST" action="{{ route('inscripciones.importar.confirmar', $curso) }}">
                @csrf
                <input type="hidden" name="gestion" value="{{ $gestion }}">
                <button type="submit" class="btn-primary">Confirmar Inscripción</button>
            </form>
        </div>

    </div>
@endsection