@extends('layouts.app')

@section('title', 'Importar estudiantes')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <x-page-header title="Importar Estudiantes" subtitle="Crea cuentas a partir del PDF exportado del SIE."
            :back-route="route('estudiantes.index')" />

        <x-error-box />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-5">
            <p class="text-sm text-gray-600">
                Suba el PDF exportado del Sistema de Información Educativa (SIE). El sistema extrae el código RUDE, carnet,
                nombre, género y fecha de
                nacimiento, y crea la persona y la cuenta de usuario.
            </p>

            <form method="POST" action="{{ route('estudiantes.importar.preview') }}" enctype="multipart/form-data"
                class="space-y-5">
                @csrf

                <div>
                    <label for="archivo" class="form-label">Archivo PDF (exportado del SIE)</label>
                    <input type="file" id="archivo" name="archivo" accept=".pdf" class="form-input" required>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('estudiantes.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Previsualizar</button>
                </div>
            </form>
        </div>

    </div>
@endsection