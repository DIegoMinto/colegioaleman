@extends('layouts.app')

@section('title', 'Nuevo trimestre')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Nuevo Trimestre</h1>
                <p class="text-sm text-gray-500 mt-1">Registra un nuevo trimestre académico.</p>
            </div>
            <a href="{{ route('trimestres.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>

        @include('components.alerts')

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('trimestres.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="nombres" class="form-label">Nombre</label>
                    <input type="text" id="nombres" name="nombres" value="{{ old('nombres') }}" class="form-input"
                        placeholder="Ej. Primer Trimestre" required>
                </div>

                <div>
                    <label for="orden" class="form-label">Orden (1, 2 o 3)</label>
                    <input type="number" id="orden" name="orden" min="1" max="3" value="{{ old('orden') }}"
                        class="form-input" required>
                </div>

                <div>
                    <label for="gestion" class="form-label">Gestión</label>
                    <input type="text" id="gestion" name="gestion" value="{{ old('gestion', date('Y')) }}"
                        class="form-input" required>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('trimestres.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">
                        Guardar Trimestre
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection