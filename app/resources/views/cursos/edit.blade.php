@extends('layouts.app')

@section('title', 'Editar curso')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-[#7A1C1C] tracking-tight">Editar Curso</h1>
                <p class="text-sm text-gray-500 mt-1">Modifica la información general del curso seleccionado.</p>
            </div>
            <a href="{{ route('cursos.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 space-y-2">
                <div class="flex items-center gap-2 font-semibold text-red-800">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Por favor corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside pl-2 space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('cursos.update', $curso) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nombre" class="form-label">Nombre del Curso</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $curso->nombre) }}"
                        class="form-input" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nivel" class="form-label">Nivel</label>
                        <input type="text" id="nivel" name="nivel" value="{{ old('nivel', $curso->nivel) }}"
                            class="form-input" required>
                    </div>

                    <div>
                        <label for="paralelo" class="form-label">Paralelo</label>
                        <input type="text" id="paralelo" name="paralelo" value="{{ old('paralelo', $curso->paralelo) }}"
                            class="form-input" required>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('cursos.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">
                        Actualizar Curso
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection