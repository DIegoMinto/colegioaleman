@extends('layouts.app')

@section('title', 'Calificaciones — Trimestres')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-4">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Ciclo Académico</p>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                    Elija el periodo de evaluación para continuar.
                </h1>
            </div>

            <form method="GET" action="{{ route('calificaciones.index') }}" class="flex items-center gap-2">
                <label for="gestion" class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                    Gestión:
                </label>
                <select name="gestion" id="gestion" onchange="this.form.submit()"
                    class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-800 shadow-sm outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 cursor-pointer">
                    @foreach ($gestiones as $gestion)
                        <option value="{{ $gestion }}" {{ $gestion == $gestionSeleccionada ? 'selected' : '' }}>
                            Gestión {{ $gestion }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($trimestres->isEmpty())
            <div class="p-8 text-center bg-white rounded-2xl border border-gray-200">
                <p class="text-gray-500 font-medium">No se encontraron trimestres registrados para la gestión
                    {{ $gestionSeleccionada }}.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($trimestres as $trimestre)
                    @php
                        $sinFechas = !$trimestre->fecha_inicio || !$trimestre->fecha_fin;
                        $esEnCurso = !$sinFechas && now()->between($trimestre->fecha_inicio, $trimestre->fecha_fin);
                        $esProximo = !$sinFechas && now()->lt($trimestre->fecha_inicio);
                        $esFinalizado = !$sinFechas && now()->gt($trimestre->fecha_fin);
                    @endphp

                    <div class="relative bg-white rounded-2xl border transition-all duration-200 p-6 flex flex-col justify-between overflow-hidden
                                                        {{ $esEnCurso ? 'border-amber-400 shadow-xl ring-1 ring-amber-400/50' : 'border-gray-200/80 shadow-md hover:shadow-lg' }}
                                                        {{ $esFinalizado ? 'opacity-75' : '' }}">

                        <div class="absolute top-0 inset-x-0 h-1.5 {{ $esEnCurso ? 'bg-amber-400' : 'bg-[var(--brand-primary)]' }}">
                        </div>

                        <div>
                            <div class="flex items-start justify-between mb-4 pt-1">
                                <img src="{{ asset('images/escudo-colegio.png') }}" alt="Escudo del colegio"
                                    class="h-20 w-20 object-contain drop-shadow-sm">

                                @if ($sinFechas)
                                    <span
                                        class="text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-gray-100 text-gray-500">
                                        Sin fechas
                                    </span>
                                @elseif ($esEnCurso)
                                    <span
                                        class="text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-orange-100 text-orange-800">
                                        En Curso
                                    </span>
                                @elseif ($esProximo)
                                    <span
                                        class="text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-gray-100 text-gray-500">
                                        Programado
                                    </span>
                                @elseif ($esFinalizado)
                                    <span
                                        class="text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-amber-100/70 text-amber-800">
                                        Finalizado
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 tracking-tight">
                                {{ $trimestre->nombres }}
                            </h3>

                            @if (!$sinFechas)
                                <p class="text-xs font-medium text-gray-500 mt-2 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $trimestre->fecha_inicio->format('d/m/Y') }} – {{ $trimestre->fecha_fin->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>

                        <a href="{{ route('calificaciones.cursos', $trimestre) }}"
                            class="mt-6 flex items-center justify-center gap-2 w-full rounded-xl px-4 py-3 text-sm font-bold shadow-md transition-all duration-200
                                                            {{ $esProximo || $sinFechas
                        ? 'bg-gray-200/80 text-brand-900 hover:bg-gray-300'
                        : 'bg-[var(--brand-primary)] text-white hover:bg-[var(--brand-primary-hover)] active:scale-[0.98]' }}">
                            Acceder
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection