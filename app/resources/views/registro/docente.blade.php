@extends('layouts.app')

@section('title', 'Registrar Docente')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <x-page-header title="Registrar Docente" subtitle="Crea el registro personal y la cuenta de acceso."
            :back-route="route('docentes.index')" />

        <x-error-box />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('registro.docente.store') }}" class="space-y-6">
                @csrf
                <x-persona-register-fields />

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('docentes.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Registrar Docente</button>
                </div>
            </form>
        </div>

    </div>
@endsection