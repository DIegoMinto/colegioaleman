@extends('layouts.app')

@section('title', 'Nueva asignación')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Nueva Asignación</h1>
                <p class="text-sm text-gray-500 mt-1">Asigna a un docente uno o varios cursos, cada uno con sus materias.
                </p>
            </div>
            <a href="{{ route('asignaciones.index') }}" class="btn-secondary">Volver</a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 space-y-2">
                <ul class="list-disc list-inside pl-2 space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('asignaciones.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="id_docentes" class="form-label">Docente</label>
                    <select id="id_docentes" name="id_docentes" class="form-input bg-white" required>
                        <option value="">-- Selecciona un docente --</option>
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->id_docentes }}" @selected(old('id_docentes') == $docente->id_docentes)>
                                {{ $docente->persona->nombres }} {{ $docente->persona->apellido_p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="gestion" class="form-label">Gestión</label>
                    <input type="text" id="gestion" name="gestion" value="{{ old('gestion', date('Y')) }}"
                        class="form-input" required>
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold text-gray-700">Cursos y materias</h2>
                        <button type="button" id="btnAgregarBloque" class="btn-secondary text-xs">
                            + Agregar curso
                        </button>
                    </div>

                    <div id="contenedorBloques" class="space-y-4"></div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('asignaciones.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Guardar Asignaciones</button>
                </div>
            </form>
        </div>
    </div>

    {{-- plantilla de un bloque (curso + sus materias) --}}
    <template id="plantillaBloque">
        <div class="bloque-curso border border-gray-200 rounded-lg p-4 bg-gray-50 space-y-3">
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <label class="form-label">Curso</label>
                    <select class="select-curso form-input bg-white" required>
                        <option value="">-- Selecciona un curso --</option>
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso->id_cursos }}">{{ $curso->nombre }} "{{ $curso->paralelo }}"</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btnEliminarBloque text-red-600 hover:text-red-800 text-sm px-2 py-2 mt-6">
                    ✕ Quitar curso
                </button>
            </div>

            <div class="pl-2 border-l-2 border-gray-200 space-y-2">
                <div class="flex items-center justify-between">
                    <label class="form-label mb-0">Materias</label>
                    <button type="button" class="btnAgregarMateria btn-secondary text-xs">+ Agregar materia</button>
                </div>
                <div class="contenedor-materias space-y-2"></div>
            </div>
        </div>
    </template>

    {{-- plantilla de una fila de materia --}}
    <template id="plantillaMateria">
        <div class="fila-materia flex gap-2 items-center">
            <select class="select-materia form-input bg-white flex-1" required>
                <option value="">-- Selecciona una materia --</option>
                @foreach ($materias as $materia)
                    <option value="{{ $materia->id_materias }}">{{ $materia->nombre }}</option>
                @endforeach
            </select>
            <button type="button" class="btnEliminarMateria text-red-600 hover:text-red-800 text-sm px-2">✕</button>
        </div>
    </template>

    <script>
        const contenedorBloques = document.getElementById('contenedorBloques');
        const plantillaBloque = document.getElementById('plantillaBloque').innerHTML;
        const plantillaMateria = document.getElementById('plantillaMateria').innerHTML;
        let contadorBloque = 0;

        // Oculta/deshabilita, dentro de un grupo de <select>, las opciones
        // que ya estén elegidas en OTRO select del mismo grupo.
        function sincronizarExclusivos(selects) {
            const seleccionados = Array.from(selects).map(s => s.value).filter(v => v !== '');
            selects.forEach(select => {
                const actual = select.value;
                Array.from(select.options).forEach(opt => {
                    if (opt.value === '') return;
                    const usadoEnOtro = seleccionados.includes(opt.value) && opt.value !== actual;
                    opt.hidden = usadoEnOtro;
                    opt.disabled = usadoEnOtro;
                });
            });
        }

        function sincronizarCursos() {
            sincronizarExclusivos(document.querySelectorAll('.select-curso'));
        }

        function sincronizarMateriasDe(bloqueEl) {
            sincronizarExclusivos(bloqueEl.querySelectorAll('.select-materia'));
        }

        function renombrarBloques() {
            document.querySelectorAll('.bloque-curso').forEach((bloque, i) => {
                bloque.querySelector('.select-curso').name = `bloques[${i}][id_cursos]`;
                bloque.querySelectorAll('.select-materia').forEach(sel => {
                    sel.name = `bloques[${i}][materias][]`;
                });
            });
        }

        function agregarMateria(bloqueEl) {
            const div = document.createElement('div');
            div.innerHTML = plantillaMateria;
            bloqueEl.querySelector('.contenedor-materias').appendChild(div.firstElementChild);
            renombrarBloques();
            sincronizarMateriasDe(bloqueEl);
        }

        function agregarBloque() {
            const div = document.createElement('div');
            div.innerHTML = plantillaBloque;
            const bloqueEl = div.firstElementChild;
            contenedorBloques.appendChild(bloqueEl);
            agregarMateria(bloqueEl); // arranca con 1 fila de materia
            renombrarBloques();
            sincronizarCursos();
        }

        contenedorBloques.addEventListener('change', (e) => {
            if (e.target.classList.contains('select-curso')) sincronizarCursos();
            if (e.target.classList.contains('select-materia')) {
                sincronizarMateriasDe(e.target.closest('.bloque-curso'));
            }
        });

        contenedorBloques.addEventListener('click', (e) => {
            if (e.target.classList.contains('btnAgregarMateria')) {
                agregarMateria(e.target.closest('.bloque-curso'));
            }
            if (e.target.classList.contains('btnEliminarMateria')) {
                const bloqueEl = e.target.closest('.bloque-curso');
                e.target.closest('.fila-materia').remove();
                renombrarBloques();
                sincronizarMateriasDe(bloqueEl);
            }
            if (e.target.classList.contains('btnEliminarBloque')) {
                e.target.closest('.bloque-curso').remove();
                renombrarBloques();
                sincronizarCursos();
            }
        });

        document.getElementById('btnAgregarBloque').addEventListener('click', agregarBloque);

        agregarBloque(); // arranca con un bloque visible
    </script>
@endsection