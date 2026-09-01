@extends('layouts.app')

@section('title', 'Configurar columnas de evaluación')

@section('content')
    <h1>{{ $asignacion->materia->nombre }} — {{ $asignacion->curso->nombre }} "{{ $asignacion->curso->paralelo }}"</h1>
    <p>{{ $trimestre->nombres }}</p>

    @if (session('exito'))
        <p style="color: green;">{{ session('exito') }}</p>
    @endif
    @if ($errors->any())
        <p style="color: red;">{{ $errors->first() }}</p>
    @endif

    @foreach ($evaluaciones as $dimension => $evaluacion)
        <fieldset style="margin-bottom: 20px; padding: 12px;">
            <legend><strong>{{ $dimension }}</strong> (vale {{ $evaluacion->porcentaje }} pts)</legend>

            <ul>
                @foreach ($evaluacion->criterios as $criterio)
                    <li>
                        {{ $criterio->nombre }}
                        @if ($dimension !== 'Decidir')
                            <form method="POST" action="{{ route('criterios.destroy', $criterio) }}" style="display:inline"
                                onsubmit="return confirm('¿Eliminar esta columna? Se perderán las notas registradas en ella.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">x</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>

            @if ($dimension !== 'Decidir')
                <form method="POST" action="{{ route('criterios.store', $evaluacion) }}">
                    @csrf
                    <input type="text" name="nombre" placeholder="Nombre de la nueva columna" required>
                    <button type="submit">+ Agregar columna</button>
                </form>
            @endif
        </fieldset>
    @endforeach

    <a href="{{ route('planilla.show', [$asignacion, $trimestre]) }}">Ir a la planilla →</a>
@endsection