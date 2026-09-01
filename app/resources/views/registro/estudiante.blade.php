@extends('layouts.app')

@section('title', 'Registrar Estudiante')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <x-page-header title="Registrar Estudiante" subtitle="Crea el registro personal y la cuenta de acceso."
            :back-route="route('estudiantes.index')" />

        <x-error-box />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('registro.estudiante.store') }}" class="space-y-6">
                @csrf
                <x-persona-register-fields />

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('estudiantes.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Registrar Estudiante</button>
                </div>
            </form>
        </div>

    </div>
@endsection