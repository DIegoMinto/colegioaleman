@extends('layouts.app')

@section('title', 'Editar estudiante')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <x-page-header title="Editar Estudiante" subtitle="Modifica los datos personales y de cuenta."
            :back-route="route('estudiantes.index')" />

        <x-error-box />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('estudiantes.update', $estudiante) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <x-persona-edit-fields :persona="$estudiante->persona" :usuario="$estudiante->persona->usuario" />

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('estudiantes.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Actualizar Estudiante</button>
                </div>
            </form>
        </div>

    </div>
@endsection