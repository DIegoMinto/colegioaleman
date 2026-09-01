@extends('layouts.app')

@section('title', 'Editar administrativo')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <x-page-header title="Editar Administrativo" subtitle="Modifica los datos personales y de cuenta."
            :back-route="route('administrativos.index')" />

        <x-error-box />

        @php $persona = $administrativo->persona;
        $usuario = $persona->usuario; @endphp

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('administrativos.update', $administrativo) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Datos Personales</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" id="nombres" name="nombres" value="{{ old('nombres', $persona->nombres) }}"
                                class="form-input" required>
                        </div>
                        <div>
                            <label for="ci" class="form-label">CI</label>
                            <input type="text" id="ci" name="ci" value="{{ old('ci', $persona->ci) }}" class="form-input"
                                required>
                        </div>
                        <div>
                            <label for="apellido_p" class="form-label">Apellido Paterno</label>
                            <input type="text" id="apellido_p" name="apellido_p"
                                value="{{ old('apellido_p', $persona->apellido_p) }}" class="form-input" required>
                        </div>
                        <div>
                            <label for="apellido_m" class="form-label">Apellido Materno</label>
                            <input type="text" id="apellido_m" name="apellido_m"
                                value="{{ old('apellido_m', $persona->apellido_m) }}" class="form-input">
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento->format('Y-m-d')) }}"
                                class="form-input" required>
                        </div>
                        <div>
                            <label for="celular" class="form-label">Celular</label>
                            <input type="text" id="celular" name="celular" value="{{ old('celular', $persona->celular) }}"
                                class="form-input">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="domicilio" class="form-label">Domicilio</label>
                            <input type="text" id="domicilio" name="domicilio"
                                value="{{ old('domicilio', $persona->domicilio) }}" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Cuenta de Acceso</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}"
                                class="form-input" required>
                        </div>
                        <div>
                            <label for="user" class="form-label">Usuario</label>
                            <input type="text" id="user" name="user" value="{{ old('user', $usuario->user) }}"
                                class="form-input" required>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('administrativos.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Actualizar Administrativo</button>
                </div>
            </form>
        </div>

    </div>
@endsection