<aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between shrink-0 min-h-screen p-4">
    <div>
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-16 h-16 mb-2 flex items-center justify-center">
                <img src="{{ asset('images/escudo-colegio.png') }}" alt="Logo"
                    class="max-h-full max-w-full object-contain">
            </div>
            <h1 class="text-sm font-bold text-red-900">Plataforma Institucional</h1>
            <span class="text-xs text-gray-500">Colegio Cardenal Maurer</span>
        </div>

        <nav class="flex flex-col space-y-1">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold text-red-900 bg-gray-100 border-l-4 border-red-900">
                <span>Inicio</span>
            </a>

            @if (auth()->user()->role->nombre === 'Administrador' || auth()->user()->role->nombre === 'Profesor')
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase mt-4 mb-2">Administración</p>

                <a href="{{ route('cursos.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Cursos</a>
                <a href="{{ route('areas.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Áreas</a>
                <a href="{{ route('materias.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Materias</a>
                <a href="{{ route('asignaciones.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Asignaciones</a>
                <a href="{{ route('trimestres.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Trimestres</a>
                <a href="{{ route('inscripciones.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Inscripciones</a>
                <a href="{{ route('estudiantes.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Estudiantes</a>
                <a href="{{ route('docentes.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Docentes</a>
                <a href="{{ route('administrativos.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Administrativos</a>
                <a href="{{ route('calificaciones.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Calificaciones</a>
            @endif
        </nav>
    </div>

    <!-- Botón Salir -->
    <div class="pt-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg">
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>