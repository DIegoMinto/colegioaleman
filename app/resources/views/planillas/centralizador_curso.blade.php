@extends('layouts.app')

@section('title', 'Centralizador de Curso')

@section('content')
    <div class="space-y-6">

        <div class="flex items-center gap-2">
            <a href="{{ route('centralizador.curso.pdf', [$trimestre, $curso]) }}" target="_blank"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-900 hover:bg-brand-950 text-white px-4 py-2 text-xs font-bold uppercase tracking-wide">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimir
            </a>
            <a href="{{ route('calificaciones.cursos', $trimestre) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-gray-600 hover:bg-gray-50">
                ← Volver
            </a>
        </div>

        @if ($estudiantes->isEmpty() || $asignaciones->isEmpty())
            <div class="p-8 text-center bg-white rounded-2xl border border-gray-200">
                <p class="text-gray-500 font-medium">
                    No hay estudiantes o materias registradas para este curso en la gestión {{ $trimestre->gestion }}.
                </p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-x-auto">
                <table class="border-collapse text-sm">
                    <thead>
                        <tr>
                            <th rowspan="2"
                                class="sticky left-0 z-20 bg-gray-50 border border-gray-200 px-2 py-2 text-[11px] font-bold text-gray-500 uppercase w-10">
                                N°
                            </th>
                            <th rowspan="2"
                                class="sticky left-10 z-20 bg-gray-50 border border-gray-200 px-3 py-2 text-[11px] font-bold text-gray-500 uppercase text-left min-w-[220px]">
                                Apellidos y Nombres
                            </th>
                            @foreach ($asignaciones as $asignacion)
                                <th class="border border-gray-200 bg-amber-50 px-1.5 py-2 h-36 w-20 min-w-[80px]">
                                    <div class="h-full flex flex-col items-start justify-end gap-1">
                                        <span
                                            class="text-[11px] font-extrabold text-gray-800 uppercase tracking-tight leading-none block"
                                            style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                            {{ $asignacion->materia->nombre }}
                                        </span>
                                        <span
                                            class="text-[9px] font-bold text-amber-700 uppercase tracking-wide leading-none block">
                                            {{ $trimestreCorto }} Trim.
                                        </span>
                                    </div>
                                </th>
                            @endforeach
                            <th rowspan="2"
                                class="border border-gray-200 bg-brand-900 text-white px-2 py-2 text-[11px] font-bold uppercase w-20">
                                Promedio
                            </th>
                        </tr>
                        <tr></tr>
                    </thead>
                    <tbody>
                        @foreach ($estudiantes as $i => $estudiante)
                                <tr class="hover:bg-amber-50/40">
                                    <td
                                        class="sticky left-0 z-10 bg-white border border-gray-200 px-2 py-1.5 text-center text-gray-500 font-medium">
                                        {{ $i + 1 }}
                                    </td>
                                    <td
                                        class="sticky left-10 z-10 bg-white border border-gray-200 px-3 py-1.5 font-semibold text-gray-800 whitespace-nowrap">
                                        {{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->apellido_m }}
                                        {{ $estudiante->persona->nombres }}
                                    </td>
                                    @foreach ($asignaciones as $asignacion)
                                        @php
                                            $nota = $promedios[$estudiante->id_estudiantes][$asignacion->id_asignaciones] ?? null;
                                        @endphp
                                        <td
                                            class="border border-gray-200 px-2 py-1.5 text-center font-bold
                                                                                                                                            {{ $nota === null ? 'text-gray-300' : ($nota < 51 ? 'text-red-600' : 'text-gray-800') }}">
                                            {{ $nota ?? '—' }}
                                        </td>
                                    @endforeach
                                    <td
                                        class="border border-gray-200 px-2 py-1.5 text-center font-extrabold
                                                                                                                    {{ ($promedioGeneral[$estudiante->id_estudiantes] ?? null) === null
                            ? 'text-gray-300'
                            : ($promedioGeneral[$estudiante->id_estudiantes] < 51 ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-brand-900') }}">
                                        {{ $promedioGeneral[$estudiante->id_estudiantes] ?? '—' }}
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection