<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class AsignacionController extends Controller
{
    public function index()
    {
        $asignaciones = Asignacion::with([
            'docente.persona' => function ($query) {
                $query->orderBy('apellido_p', 'asc')
                    ->orderBy('apellido_m', 'asc')
                    ->orderBy('nombres', 'asc');
            },
            'curso' => function ($query) {
                $query->orderBy('nombre', 'asc');
            },
            'materia'
        ])
            ->orderBy('gestion', 'desc')
            ->get();

        $asignaciones = $asignaciones->sortBy([
            ['docente.persona.apellido_p', 'asc'],
            ['docente.persona.apellido_m', 'asc'],
            ['docente.persona.nombres', 'asc'],
        ]);

        $docentes = $asignaciones->pluck('docente')->filter()->unique(function ($docente) {
            return $docente->id_docentes ?? $docente->id_docente ?? $docente->id;
        });

        $cursos = $asignaciones->pluck('curso.nombre')->filter()->unique()->values();
        $niveles = $asignaciones->pluck('curso.nivel')->filter()->unique()->values();
        $paralelos = $asignaciones->pluck('curso.paralelo')->filter()->unique()->values();

        return view('asignaciones.index', compact('asignaciones', 'docentes', 'cursos', 'niveles', 'paralelos'));
    }

    public function create()
    {
        return view('asignaciones.create', $this->datosParaFormulario());
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'id_docentes' => ['required', 'exists:docentes,id_docentes'],
            'gestion' => ['required', 'string', 'max:10'],
            'bloques' => ['required', 'array', 'min:1'],
            'bloques.*.id_cursos' => ['required', 'exists:cursos,id_cursos', 'distinct'],
            'bloques.*.materias' => ['required', 'array', 'min:1'],
            'bloques.*.materias.*' => ['required', 'exists:materias,id_materias'],
        ], [
            'bloques.*.id_cursos.distinct' => 'No puedes repetir el mismo curso en dos bloques.',
        ]);

        // 'distinct' con doble wildcard no aísla por bloque, así que
        // el chequeo de materias repetidas DENTRO de un mismo curso se hace manual
        foreach ($datos['bloques'] as $indice => $bloque) {
            if (collect($bloque['materias'])->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    "bloques.$indice.materias" => 'No puedes repetir la misma materia dentro del mismo curso.',
                ]);
            }
        }

        DB::transaction(function () use ($datos) {
            foreach ($datos['bloques'] as $bloque) {
                foreach ($bloque['materias'] as $idMateria) {
                    $yaExiste = Asignacion::where('id_docentes', $datos['id_docentes'])
                        ->where('id_cursos', $bloque['id_cursos'])
                        ->where('id_materias', $idMateria)
                        ->where('gestion', $datos['gestion'])
                        ->exists();

                    if ($yaExiste) {
                        throw ValidationException::withMessages([
                            'bloques' => 'Ya existe una asignación igual (docente, curso, materia, gestión) en la base de datos.',
                        ]);
                    }

                    Asignacion::create([
                        'id_docentes' => $datos['id_docentes'],
                        'id_cursos' => $bloque['id_cursos'],
                        'id_materias' => $idMateria,
                        'gestion' => $datos['gestion'],
                    ]);
                }
            }
        });

        return redirect()->route('asignaciones.index')
            ->with('exito', 'Asignaciones registradas correctamente.');
    }

    public function edit(Asignacion $asignacion)
    {
        return view('asignaciones.edit', array_merge(
            ['asignacion' => $asignacion],
            $this->datosParaFormulario()
        ));
    }

    public function update(Request $request, Asignacion $asignacion)
    {
        $datos = $this->validarDatos($request, $asignacion);

        $asignacion->update($datos);

        return redirect()->route('asignaciones.index')->with('exito', 'Asignación actualizada correctamente.');
    }

    public function destroy(Asignacion $asignacion)
    {
        try {
            $asignacion->delete();
        } catch (QueryException $e) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'No se pudo eliminar: esta asignación tiene registros relacionados (por ejemplo, calificaciones) asociados.');
        }

        return redirect()->route('asignaciones.index')->with('exito', 'Asignación eliminada correctamente.');
    }

    private function datosParaFormulario(): array
    {
        return [
            'docentes' => Docente::with('persona')->get(),
            'cursos' => Curso::orderBy('nivel')->orderBy('paralelo')->get(),
            'materias' => Materia::orderBy('nombre')->get(),
        ];
    }

    private function validarDatos(Request $request, ?Asignacion $asignacionActual = null): array
    {
        return $request->validate([
            'id_docentes' => ['required', 'exists:docentes,id_docentes'],
            'id_cursos' => ['required', 'exists:cursos,id_cursos'],
            'id_materias' => ['required', 'exists:materias,id_materias'],
            'gestion' => [
                'required',
                'string',
                'max:10',
                Rule::unique('asignaciones')
                    ->where(fn($query) => $query
                        ->where('id_docentes', $request->id_docentes)
                        ->where('id_cursos', $request->id_cursos)
                        ->where('id_materias', $request->id_materias)
                        ->where('gestion', $request->gestion))
                    ->ignore($asignacionActual?->id_asignaciones, 'id_asignaciones'),
            ],
        ], [
            'gestion.unique' => 'Ya existe esta misma asignación (docente, curso, materia y gestión) registrada.',
        ]);
    }
}