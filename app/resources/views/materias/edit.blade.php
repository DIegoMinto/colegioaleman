@extends('layouts.app')

@section('title', 'Editar materia')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Header con botón Volver -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-[#7A1C1C] tracking-tight">Editar Materia</h1>
                <p class="text-sm text-gray-500 mt-1">Modifica la información general de la materia.</p>
            </div>
            <a href="{{ route('materias.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>

        <!-- Caja de Errores -->
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

        <!-- Formulario Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('materias.update', $materia) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nombre" class="form-label">Nombre de la Materia</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $materia->nombre) }}"
                        class="form-input" required>
                </div>

                <div>
                    <label for="id_areas" class="form-label">Área de la Materia</label>
                    <select id="id_areas" name="id_areas" class="form-input bg-white" required>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id_areas }}" @selected(old('id_areas', $materia->id_areas) == $area->id_areas)>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('materias.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">
                        Actualizar Materia
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection