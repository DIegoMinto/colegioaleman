@extends('layouts.app')

@section('title', 'Inscripción masiva')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <x-page-header title="Inscripción Masiva" subtitle="{{ $curso->nombre }} &quot;{{ $curso->paralelo }}&quot;"
            :back-route="route('inscripciones.show', ['curso' => $curso, 'gestion' => date('Y')])" />

        <x-error-box />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-5">
            <p class="text-sm text-gray-600">
                Sube el PDF exportado del SIE para este curso. El sistema busca cada carnet en el sistema y
                los inscribe si ya tienen una cuenta creada. Si algún estudiante del PDF no existe todavía,
                aparecerá marcado para que lo importes primero desde "Estudiantes → Importar Lista".
            </p>

            <form method="POST" action="{{ route('inscripciones.importar.preview', $curso) }}" enctype="multipart/form-data"
                class="space-y-5">
                @csrf

                <div>
                    <label for="gestion" class="form-label">Gestión</label>
                    <input type="number" id="gestion" name="gestion" value="{{ old('gestion', date('Y')) }}"
                        class="form-input" required>
                </div>

                <div>
                    <label for="archivo" class="form-label">Archivo PDF (exportado del SIE)</label>
                    <input type="file" id="archivo" name="archivo" accept=".pdf" class="form-input" required>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('inscripciones.show', ['curso' => $curso, 'gestion' => date('Y')]) }}"
                        class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Previsualizar</button>
                </div>
            </form>
        </div>

    </div>
@endsection