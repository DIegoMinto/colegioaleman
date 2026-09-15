<table>
    <thead>
        <tr>
            <th>#</th>
            <th class="izq">Nombre Completo</th>
            <th>CI</th>
            @if($mostrarRude ?? false)
                <th>RUDE</th>
            @endif
            <th>Sexo</th>
            <th>Celular</th>
            <th>Departamento</th>
            <th class="izq">Correo</th>
            <th>Usuario</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registros as $i => $registro)
            @php
                $persona = $registro->persona;
                $usuario = $persona->usuario;
            @endphp
            <tr>
                <td class="centro">{{ $i + 1 }}</td>
                <td class="izq mayus">{{ $persona->nombres }} {{ $persona->apellido_p }} {{ $persona->apellido_m }}</td>
                <td class="centro">{{ $persona->ci }}</td>
                @if($mostrarRude ?? false)
                    <td class="centro">{{ $registro->rude ?? '-' }}</td>
                @endif
                <td class="centro">{{ $persona->sexo ?? '-' }}</td>
                <td class="centro">{{ $persona->celular ?? '-' }}</td>
                <td class="centro">{{ $persona->departamento_residencia ?? '-' }}</td>
                <td class="izq">{{ $usuario->email ?? '-' }}</td>
                <td class="centro">{{ $usuario->user ?? '-' }}</td>
                <td class="centro">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>