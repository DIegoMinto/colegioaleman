@extends('layouts.app')

@section('title', 'Asignaciones')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-brand-900 tracking-tight">Asignaciones</h1>
                <p class="text-sm text-gray-500 mt-1">Docentes asignados a cursos y materias por gestión.</p>
            </div>
            <div>
                <a href="{{ route('asignaciones.create') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Asignación
                </a>
            </div>
        </div>

        @if (session('exito'))
            <div
                class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('exito') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Curso</th>
                        <th>Materia</th>
                        <th>Gestión</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($asignaciones as $asignacion)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="font-bold text-gray-900">
                                {{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellido_p }}
                            </td>
                            <td>{{ $asignacion->curso->nombre }} "{{ $asignacion->curso->paralelo }}"</td>
                            <td>{{ $asignacion->materia->nombre }}</td>
                            <td>{{ $asignacion->gestion }}</td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('asignaciones.edit', $asignacion) }}" class="btn-secondary">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </a>

                                <button type="button" class="btn-danger-sm btnEliminar"
                                    data-form-id="form-eliminar-{{ $asignacion->id_asignaciones }}"
                                    data-mensaje="¿Seguro que quieres eliminar la asignación de {{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellido_p }} en {{ $asignacion->curso->nombre }} &quot;{{ $asignacion->curso->paralelo }}&quot; ({{ $asignacion->materia->nombre }})?">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar
                                </button>

                                <form id="form-eliminar-{{ $asignacion->id_asignaciones }}" method="POST"
                                    action="{{ route('asignaciones.destroy', $asignacion) }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal de confirmación (uno solo, reutilizado por todas las filas) --}}
    <div id="modalEliminar" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div id="modalEliminarFondo" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>

        <div class="relative bg-white rounded-xl border border-gray-200 shadow-lg w-full max-w-sm mx-4 p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h2 class="text-base font-semibold text-gray-900">Eliminar asignación</h2>
            </div>

            <p id="modalEliminarMensaje" class="text-sm text-gray-600"></p>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="modalEliminarCancelar" class="btn-secondary">Cancelar</button>
                <button type="button" id="modalEliminarConfirmar" class="btn-danger-sm">Sí, eliminar</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalEliminar');
        const modalMensaje = document.getElementById('modalEliminarMensaje');
        let formAEliminar = null;

        document.querySelectorAll('.btnEliminar').forEach(btn => {
            btn.addEventListener('click', () => {
                formAEliminar = document.getElementById(btn.dataset.formId);
                modalMensaje.textContent = btn.dataset.mensaje;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        function cerrarModalEliminar() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            formAEliminar = null;
        }

        document.getElementById('modalEliminarCancelar').addEventListener('click', cerrarModalEliminar);
        document.getElementById('modalEliminarFondo').addEventListener('click', cerrarModalEliminar);

        document.getElementById('modalEliminarConfirmar').addEventListener('click', () => {
            if (formAEliminar) formAEliminar.submit();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') cerrarModalEliminar();
        });
    </script>
@endsection