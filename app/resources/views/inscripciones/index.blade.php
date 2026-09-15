@extends('layouts.app')

@section('title', 'Inscripciones')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Inscripciones por Curso</h1>
                <p class="text-sm text-gray-500 mt-1">Gestiona las inscripciones de estudiantes en cada curso.</p>
            </div>

            <form method="GET" action="{{ route('inscripciones.index') }}" class="flex items-center gap-2">
                <label for="gestion" class="text-sm font-semibold text-gray-700 shrink-0">Gestión:</label>
                <select name="gestion" id="gestion" onchange="this.form.submit()"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2">
                    @foreach($gestiones as $g)
                        <option value="{{ $g }}" {{ $g == $gestionSeleccionada ? 'selected' : '' }}>
                            {{ $g }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Inscritos ({{ $gestionSeleccionada }})</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($cursos as $curso)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-bold text-gray-900">{{ $curso->nombre }} "{{ $curso->paralelo }}"</td>
                            <td>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-brand-900">
                                    {{ $curso->inscripciones_count }} inscritos
                                </span>
                            </td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('inscripciones.show', ['curso' => $curso, 'gestion' => $gestionSeleccionada]) }}"
                                    class="btn-secondary">
                                    Ver lista
                                </a>
                                @if($gestionSeleccionada == date('Y'))
                                    <a href="{{ route('inscripciones.create', $curso) }}" class="btn-secondary">
                                        Inscribir
                                    </a>
                                    <a href="{{ route('inscripciones.importar.form', $curso) }}" class="btn-secondary">
                                        Inscribir desde PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection