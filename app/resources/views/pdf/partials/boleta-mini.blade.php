<div class="max-w-3xl mx-auto">

    {{-- Encabezado: escudo a la izquierda + datos a la derecha --}}
    <table class="w-full border-collapse border-2 border-black">
        <tbody>
            <tr>
                <td class="border-2 border-black p-1 text-center align-middle" rowspan="3" style="width: 70px;">
                    <img src="{{ asset('images/escudo-colegio.png') }}" alt="Escudo Colegio"
                        class="h-12 mx-auto object-contain">
                </td>
                <td class="border-2 border-black p-1 text-center font-bold uppercase text-xs bg-gray-100" colspan="4">
                    U.E. Boliviano Alemán Cardenal Maurer
                </td>
            </tr>
            <tr>
                <td class="border-2 border-black p-1 font-bold uppercase text-center bg-gray-100 w-1/6">Número</td>
                <td class="border-2 border-black p-1 text-center  w-1/6">{{ $numeroLista ?? '-' }}</td>
                <td class="border-2 border-black p-1 font-bold uppercase text-center bg-gray-100 w-1/6">Curso</td>
                <td class="border-2 border-black p-1 font-bold uppercase text-center ">
                    {{ $curso->nombre }} {{ $curso->paralelo }}
                </td>
            </tr>
            <tr>
                <td class="border-2 border-black p-1 font-bold uppercase text-center bg-gray-100">Alumno</td>
                <td class="border-2 border-black p-1 font-bold uppercase text-center " colspan="3">
                    {{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->apellido_m }}
                    {{ $estudiante->persona->nombres }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Tabla de notas --}}
    <table class="w-full border-collapse border-2 border-t-0 border-black text-center">
        <thead>
            <tr>
                <th class="border-2 border-black p-0.5 font-bold uppercase" rowspan="2">Areas</th>
                <th class="border-2 border-black p-0.5 font-bold uppercase" colspan="3">Trimestres</th>
                <th class="border-2 border-black p-0.5 font-bold uppercase" colspan="2">Valoración Cuantitativa</th>
            </tr>
            <tr>
                <th class="border-2 border-black p-0.5 font-bold uppercase w-8">1er</th>
                <th class="border-2 border-black p-0.5 font-bold uppercase w-8">2do</th>
                <th class="border-2 border-black p-0.5 font-bold uppercase w-8">3er</th>
                <th class="border-2 border-black p-0.5 font-bold uppercase w-12 bg-gray-100">Numeral</th>
                <th class="border-2 border-black p-0.5 font-bold uppercase">Literal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($curso->asignaciones as $asignacion)
                <tr>
                    <td class="border-2 border-black p-0.5 uppercase text-left pl-1">
                        {{ $asignacion->materia->nombre ?? 'Materia' }}
                    </td>
                    <td class="border-2 border-black p-0.5">{{ $notas[$asignacion->id_asignaciones][1] ?? '-' }}</td>
                    <td class="border-2 border-black p-0.5">{{ $notas[$asignacion->id_asignaciones][2] ?? '-' }}</td>
                    <td class="border-2 border-black p-0.5">{{ $notas[$asignacion->id_asignaciones][3] ?? '-' }}</td>
                    <td class="border-2 border-black p-0.5 bg-gray-100">
                        {{ $promedios[$asignacion->id_asignaciones] ?? '-' }}
                    </td>
                    <td class="border-2 border-black p-0.5 uppercase text-left pl-1 ">
                        {{ $promediosLiteral[$asignacion->id_asignaciones] ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border-2 border-black p-2 text-gray-500">
                        No existen asignaciones registradas.
                    </td>
                </tr>
            @endforelse

            <tr>
                <td class="border-2 border-black p-0.5 font-bold uppercase text-left pl-1">Promedios</td>
                <td class="border-2 border-black p-0.5 font-bold">{{ $trimestre == 1 ? $promedioGeneral : '' }}</td>
                <td class="border-2 border-black p-0.5 font-bold">{{ $trimestre == 2 ? $promedioGeneral : '' }}</td>
                <td class="border-2 border-black p-0.5 font-bold">{{ $trimestre == 3 ? $promedioGeneral : '' }}</td>
                <td class="border-2 border-black p-0.5 bg-gray-100"></td>
                <td class="border-2 border-black p-0.5"></td>
            </tr>
        </tbody>
    </table>

</div>