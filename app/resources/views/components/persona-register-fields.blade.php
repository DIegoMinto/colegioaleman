<div>
    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Datos Personales</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" id="nombres" name="nombres" value="{{ old('nombres') }}" class="form-input" required>
        </div>
        <div>
            <label for="ci" class="form-label">CI</label>
            <input type="text" id="ci" name="ci" value="{{ old('ci') }}" class="form-input" required>
        </div>
        <div>
            <label for="apellido_p" class="form-label">Apellido Paterno</label>
            <input type="text" id="apellido_p" name="apellido_p" value="{{ old('apellido_p') }}" class="form-input"
                required>
        </div>
        <div>
            <label for="apellido_m" class="form-label">Apellido Materno</label>
            <input type="text" id="apellido_m" name="apellido_m" value="{{ old('apellido_m') }}" class="form-input">
        </div>
        <div>
            <label for="sexo" class="form-label">Sexo</label>
            <select id="sexo" name="sexo" class="form-input bg-white" required>
                <option value="">-- Selecciona --</option>
                <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
            </select>
        </div>
        <div>
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                class="form-input" required>
        </div>
        <div>
            <label for="celular" class="form-label">Celular</label>
            <input type="text" id="celular" name="celular" value="{{ old('celular') }}" class="form-input">
        </div>
        <div>
            <label for="departamento_residencia" class="form-label">Departamento de Residencia</label>
            <select id="departamento_residencia" name="departamento_residencia" class="form-input bg-white">
                <option value="">-- Selecciona --</option>
                @foreach (['La Paz', 'Cochabamba', 'Santa Cruz', 'Oruro', 'Potosí', 'Chuquisaca', 'Tarija', 'Beni', 'Pando'] as $depto)
                    <option value="{{ $depto }}" @selected(old('departamento_residencia') === $depto)>{{ $depto }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-2">
            <label for="domicilio" class="form-label">Domicilio</label>
            <input type="text" id="domicilio" name="domicilio" value="{{ old('domicilio') }}" class="form-input">
        </div>
    </div>
</div>

<div class="pt-6 border-t border-gray-100">
    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Cuenta de Acceso</h2>
    <p class="text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-lg p-3">
        El <strong>correo institucional</strong> y el <strong>usuario</strong> se generarán automáticamente con el
        formato <code
            class="bg-white px-1 rounded border border-gray-200">nombre.apellidopaterno.apellidomaterno</code>,
        y la <strong>contraseña inicial</strong> será el número de carnet (CI) registrado.
    </p>
</div>