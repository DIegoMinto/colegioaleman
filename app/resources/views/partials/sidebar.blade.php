<aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between shrink-0 h-screen sticky top-0 p-4">

    <div id="sidebar-nav" up-scroll="keep"
        class="overflow-y-auto flex-1 pr-1 space-y-6 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

        <div class="flex flex-col items-center text-center mb-4">
            <div class="w-16 h-16 mb-2 flex items-center justify-center">
                <img src="{{ asset('images/escudo-colegio.png') }}" alt="Logo"
                    class="max-h-full max-w-full object-contain">
            </div>
            <h1 class="text-sm font-bold text-brand-900">Plataforma Institucional</h1>
            <span class="text-xs text-gray-500">Colegio Cardenal Maurer</span>
        </div>

        @php $rol = auth()->user()->role->nombre; @endphp

        <nav class="flex flex-col space-y-5" up-nav up-target="#main-content" up-follow up-scroll="restore">

            <a href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Inicio</span>
            </a>

            @if ($rol === 'Administrador')

                <div class="bg-gray-50/80 rounded-xl p-2 border border-gray-100/80 space-y-1">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider my-1">Gestión</p>
                    <a href="{{ route('trimestres.index') }}"
                        class="{{ request()->routeIs('trimestres.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Trimestres</span>
                    </a>
                    <a href="{{ route('asignaciones.index') }}"
                        class="{{ request()->routeIs('asignaciones.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Asignaciones</span>
                    </a>
                    <a href="{{ route('inscripciones.index') }}"
                        class="{{ request()->routeIs('inscripciones.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Inscripciones</span>
                    </a>
                    <a href="{{ route('calificaciones.index') }}"
                        class="{{ request()->routeIs('calificaciones.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Calificaciones</span>
                    </a>
                </div>

                <div class="bg-gray-50/80 rounded-xl p-2 border border-gray-100/80 space-y-1">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider my-1">Recursos Humanos</p>
                    <a href="{{ route('estudiantes.index') }}"
                        class="{{ request()->routeIs('estudiantes.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        <span>Estudiantes</span>
                    </a>
                    <a href="{{ route('docentes.index') }}"
                        class="{{ request()->routeIs('docentes.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Docentes</span>
                    </a>
                    <a href="{{ route('administrativos.index') }}"
                        class="{{ request()->routeIs('administrativos.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Administrativos</span>
                    </a>
                </div>

                <div class="bg-gray-50/80 rounded-xl p-2 border border-gray-100/80 space-y-1">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider my-1">Estructura</p>
                    <a href="{{ route('cursos.index') }}"
                        class="{{ request()->routeIs('cursos.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0V5" />
                        </svg>
                        <span>Cursos</span>
                    </a>
                    <a href="{{ route('areas.index') }}"
                        class="{{ request()->routeIs('areas.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Áreas</span>
                    </a>
                    <a href="{{ route('materias.index') }}"
                        class="{{ request()->routeIs('materias.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Materias</span>
                    </a>
                </div>

            @elseif ($rol === 'Secretaría')

                <div class="bg-gray-50/80 rounded-xl p-2 border border-gray-100/80 space-y-1">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider my-1">Gestión</p>
                    <a href="{{ route('calificaciones.index') }}"
                        class="{{ request()->routeIs('calificaciones.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Calificaciones</span>
                    </a>
                </div>

                <div class="bg-gray-50/80 rounded-xl p-2 border border-gray-100/80 space-y-1">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider my-1">Recursos Humanos</p>
                    <a href="{{ route('estudiantes.index') }}"
                        class="{{ request()->routeIs('estudiantes.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        <span>Estudiantes</span>
                    </a>
                </div>

                <div class="bg-gray-50/80 rounded-xl p-2 border border-gray-100/80 space-y-1">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider my-1">Estructura</p>
                    <a href="{{ route('cursos.index') }}"
                        class="{{ request()->routeIs('cursos.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0V5" />
                        </svg>
                        <span>Cursos</span>
                    </a>
                    <a href="{{ route('areas.index') }}"
                        class="{{ request()->routeIs('areas.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Áreas</span>
                    </a>
                    <a href="{{ route('materias.index') }}"
                        class="{{ request()->routeIs('materias.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Materias</span>
                    </a>
                </div>

            @elseif ($rol === 'Profesor')

                <div class="bg-gray-50/80 rounded-xl p-2 border border-gray-100/80 space-y-1">
                    <a href="{{ route('calificaciones.index') }}"
                        class="{{ request()->routeIs('calificaciones.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Calificaciones</span>
                    </a>
                </div>

            @elseif ($rol === 'Estudiante')

                <div class="bg-gray-50/80 rounded-xl p-2 border border-gray-100/80 space-y-1">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider my-1">Mi Información</p>
                    <a href="{{ route('mis-boletines.index') }}"
                        class="{{ request()->routeIs('mis-boletines.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Mis Boletines</span>
                    </a>
                </div>

            @endif
        </nav>
    </div>

    <div class="pt-4 border-t border-gray-100 mt-2 bg-white shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>