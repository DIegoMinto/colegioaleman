@extends('layouts.app')

@section('title', 'Inscripciones')

@section('content')
    <div class="space-y-6">

        <div class="pb-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Inscripciones por Curso</h1>
            <p class="text-sm text-gray-500 mt-1">Gestiona las inscripciones de estudiantes en cada curso.</p>
        </div>

        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Estudiantes Inscritos</th>
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
                                    {{ $curso->inscripciones_count }}
                                </span>
                            </td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('inscripciones.show', $curso) }}" class="btn-secondary">
                                    Ver lista
                                </a>
                                <a href="{{ route('inscripciones.create', $curso) }}" class="btn-secondary">
                                    Inscribir
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection