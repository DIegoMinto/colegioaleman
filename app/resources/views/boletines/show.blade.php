@extends('layouts.app')

@section('title', 'Libreta Escolar Electrónica')

@section('content')

<a href="{{ route('boletines.pdf', [$inscripcion, $trimestre]) }}" target="_blank"
    class="inline-flex items-center gap-2 rounded-lg bg-brand-900 hover:bg-brand-950 text-white px-4 py-2 text-xs font-bold uppercase tracking-wide">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
    </svg>
    Imprimir
</a>

    <div class="max-w-3xl mx-auto my-6 bg-white text-black font-sans text-xs print:my-0">

        <table class="w-full border-collapse border-2 border-black">
            <tbody>
                <tr>
                    <td class="border-2 border-black p-2 text-center align-middle" rowspan="3" style="width: 90px;">
                        <img src="{{ asset('images/escudo-colegio.png') }}" alt="Escudo Colegio"
                            class="h-16 mx-auto object-contain">
                    </td>
                    <td class="border-2 border-black p-2 text-center font-bold uppercase text-sm bg-gray-100" colspan="4">
                        U.E. Boliviano Alemán Cardenal Maurer
                    </td>
                </tr>
                <tr>
                    <td class="border-2 border-black p-1 font-bold uppercase text-center bg-gray-100 w-1/6">Número</td>
                    <td class="border-2 border-black p-1 text-center">{{ $numeroLista ?? '-' }}</td>
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

        <table class="w-full border-collapse border-2 border-t-0 border-black text-center">
            <thead>
                <tr>
                    <th class="border-2 border-black p-1 font-bold uppercase" rowspan="2">Areas</th>
                    <th class="border-2 border-black p-1 font-bold uppercase" colspan="3">Trimestres</th>
                    <th class="border-2 border-black p-1 font-bold uppercase" colspan="2">Valoración Cuantitativa</th>
                </tr>
                <tr>
                    <th class="border-2 border-black p-1 font-bold uppercase w-10">1er</th>
                    <th class="border-2 border-black p-1 font-bold uppercase w-10">2do</th>
                    <th class="border-2 border-black p-1 font-bold uppercase w-10">3er</th>
                    <th class="border-2 border-black p-1 font-bold uppercase w-14 bg-gray-100">Numeral</th>
                    <th class="border-2 border-black p-1 font-bold uppercase">Literal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($curso->asignaciones as $asignacion)
                    <tr>
                        <td class="border-2 border-black p-1 uppercase text-left pl-2">
                            {{ $asignacion->materia->nombre ?? 'Materia' }}
                        </td>
                        <td class="border-2 border-black p-1">{{ $notas[$asignacion->id_asignaciones][1] ?? '-' }}</td>
                        <td class="border-2 border-black p-1">{{ $notas[$asignacion->id_asignaciones][2] ?? '-' }}</td>
                        <td class="border-2 border-black p-1">{{ $notas[$asignacion->id_asignaciones][3] ?? '-' }}</td>
                        <td class="border-2 border-black p-1 bg-gray-100">{{ $promedios[$asignacion->id_asignaciones] ?? '-' }}</td>
                        <td class="border-2 border-black p-1 uppercase text-left pl-2">
                            {{ $promediosLiteral[$asignacion->id_asignaciones] ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border-2 border-black p-4 text-gray-500">
                            No existen asignaciones de materias registradas para este curso.
                        </td>
                    </tr>
                @endforelse

                @for ($i = 0; $i < 2; $i++)
                    <tr>
                        <td class="border-2 border-black p-2">&nbsp;</td>
                        <td class="border-2 border-black p-2">&nbsp;</td>
                        <td class="border-2 border-black p-2">&nbsp;</td>
                        <td class="border-2 border-black p-2">&nbsp;</td>
                        <td class="border-2 border-black p-2">&nbsp;</td>
                        <td class="border-2 border-black p-2">&nbsp;</td>
                    </tr>
                @endfor

                {{-- Fila PROMEDIOS --}}
                <tr>
                    <td class="border-2 border-black p-1 font-bold uppercase text-left pl-2">Promedios</td>
                    <td class="border-2 border-black p-1 font-bold">{{ $trimestre == 1 ? $promedioGeneral : '' }}</td>
                    <td class="border-2 border-black p-1 font-bold">{{ $trimestre == 2 ? $promedioGeneral : '' }}</td>
                    <td class="border-2 border-black p-1 font-bold">{{ $trimestre == 3 ? $promedioGeneral : '' }}</td>
                    <td class="border-2 border-black p-1"></td>
                    <td class="border-2 border-black p-1"></td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection