@props(['persona', 'usuario'])

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
            <input type="text" id="ci" name="ci" value="{{ old('ci', $persona->ci) }}" class="form-input" required>
        </div>
        <div>
            <label for="apellido_p" class="form-label">Apellido Paterno</label>
            <input type="text" id="apellido_p" name="apellido_p" value="{{ old('apellido_p', $persona->apellido_p) }}"
                class="form-input" required>
        </div>
        <div>
            <label for="apellido_m" class="form-label">Apellido Materno</label>
            <input type="text" id="apellido_m" name="apellido_m" value="{{ old('apellido_m', $persona->apellido_m) }}"
                class="form-input">
        </div>
        <div>
            <label for="sexo" class="form-label">Sexo</label>
            <select id="sexo" name="sexo" class="form-input bg-white" required>
                <option value="">-- Selecciona --</option>
                <option value="M" @selected(old('sexo', $persona->sexo) === 'M')>Masculino</option>
                <option value="F" @selected(old('sexo', $persona->sexo) === 'F')>Femenino</option>
            </select>
        </div>
        <div>
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                value="{{ old('fecha_nacimiento', optional($persona->fecha_nacimiento)->format('Y-m-d')) }}"
                class="form-input" required>
        </div>
        <div>
            <label for="celular" class="form-label">Celular</label>
            <input type="text" id="celular" name="celular" value="{{ old('celular', $persona->celular) }}"
                class="form-input">
        </div>
        <div class="sm:col-span-2">
            <label for="domicilio" class="form-label">Domicilio</label>
            <input type="text" id="domicilio" name="domicilio" value="{{ old('domicilio', $persona->domicilio) }}"
                class="form-input">
        </div>
    </div>
</div>

<div class="pt-6 border-t border-gray-100">
    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Cuenta de Acceso</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="email" class="form-label">Correo</label>
            <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" class="form-input"
                required>
        </div>
        <div>
            <label for="user" class="form-label">Usuario</label>
            <input type="text" id="user" name="user" value="{{ old('user', $usuario->user) }}" class="form-input"
                required>
        </div>
        <div class="sm:col-span-2">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-input"
                placeholder="Dejar en blanco para no cambiarla">
            <p class="text-xs text-gray-500 mt-1">Si no llenas este campo, la contraseña actual se mantiene sin
                cambios.</p>
        </div>
    </div>
</div>