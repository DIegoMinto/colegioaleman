@extends('layouts.app')

@section('title', 'Calificaciones — Trimestres')

@section('content')
    <div class="space-y-6">

        <div>
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Ciclo Académico Actual</p>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight mt-1">
                Elija el periodo de evaluación para continuar.
            </h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($trimestres as $trimestre)
                @php
                    $estado = $trimestre->estado; // 'finalizado' | 'en_curso' | 'programado' | null
                    $esActual = $estado === 'en_curso';
                    $esFinalizado = $estado === 'finalizado';
                @endphp

                <div
                    class="relative bg-white rounded-xl border {{ $esActual ? 'border-amber-400 shadow-md' : 'border-gray-200 shadow-sm' }} p-6 {{ $esFinalizado ? 'opacity-70' : '' }} transition-all">

                    @if ($esActual)
                        <div class="absolute inset-x-0 top-0 h-1 bg-amber-400 rounded-t-xl"></div>
                    @endif

                    <div class="flex items-start justify-between mb-6">
                        <img src="{{ asset('images/escudo-colegio.png') }}" alt="Escudo del colegio"
                            class="h-10 w-10 object-contain">

                        @if ($estado === 'finalizado')
                            <span
                                class="text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded-full bg-amber-50 text-amber-700">
                                Finalizado
                            </span>
                        @elseif ($estado === 'en_curso')
                            <span
                                class="text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded-full bg-amber-100 text-amber-800">
                                En Curso
                            </span>
                        @elseif ($estado === 'programado')
                            <span
                                class="text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                Programado
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">{{ $trimestre->nombres }}</h3>

                    @if ($trimestre->fecha_inicio && $trimestre->fecha_fin)
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $trimestre->fecha_inicio->format('d/m/y') }} - {{ $trimestre->fecha_fin->format('d/m/y') }}
                        </p>
                    @endif

                    <a href="{{ route('calificaciones.cursos', $trimestre) }}"
                        class="mt-5 flex items-center justify-center gap-1.5 w-full rounded-lg px-4 py-2.5 text-sm font-semibold transition-colors
                                {{ $esFinalizado ? 'bg-gray-100 text-gray-400' : ($estado === 'programado' ? 'bg-gray-100 text-brand-900' : 'bg-brand-900 text-white hover:bg-brand-950') }}">
                        Acceder
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
@endsection