@extends('layouts.app')

@section('title', 'Editar asignación')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Editar Asignación</h1>
                <p class="text-sm text-gray-500 mt-1">Modifica el docente, curso, materia o gestión de esta asignación.</p>
            </div>
            <a href="{{ route('asignaciones.index') }}" class="btn-secondary">Volver</a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 space-y-2">
                <ul class="list-disc list-inside pl-2 space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('asignaciones.update', $asignacion) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="id_docentes" class="form-label">Docente</label>
                    <select id="id_docentes" name="id_docentes" class="form-input bg-white" required>
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->id_docentes }}" @selected(old('id_docentes', $asignacion->id_docentes) == $docente->id_docentes)>
                                {{ $docente->persona->nombres }} {{ $docente->persona->apellido_p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_cursos" class="form-label">Curso</label>
                    <select id="id_cursos" name="id_cursos" class="form-input bg-white" required>
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso->id_cursos }}" @selected(old('id_cursos', $asignacion->id_cursos) == $curso->id_cursos)>
                                {{ $curso->nombre }} "{{ $curso->paralelo }}"
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_materias" class="form-label">Materia</label>
                    <select id="id_materias" name="id_materias" class="form-input bg-white" required>
                        @foreach ($materias as $materia)
                            <option value="{{ $materia->id_materias }}" @selected(old('id_materias', $asignacion->id_materias) == $materia->id_materias)>
                                {{ $materia->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="gestion" class="form-label">Gestión</label>
                    <input type="text" id="gestion" name="gestion" value="{{ old('gestion', $asignacion->gestion) }}"
                        class="form-input" required>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('asignaciones.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Actualizar Asignación</button>
                </div>
            </form>
        </div>
    </div>
@endsection