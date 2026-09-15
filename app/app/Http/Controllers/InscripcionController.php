<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    public function index(Request $request)
    {
        $gestionSeleccionada = $request->get('gestion', date('Y'));

        $gestiones = range(date('Y'), 2024);

        $cursos = Curso::withCount([
            'inscripciones' => function ($query) use ($gestionSeleccionada) {
                $query->where('gestion', $gestionSeleccionada);
            }
        ])->orderBy('nivel')->orderBy('paralelo')->get();

        return view('inscripciones.index', compact('cursos', 'gestionSeleccionada', 'gestiones'));
    }

    public function show(Request $request, Curso $curso)
    {
        $gestionSeleccionada = $request->get('gestion', date('Y'));

        $inscripciones = Inscripcion::with('estudiante.persona')
            ->where('id_cursos', $curso->id_cursos)
            ->where('gestion', $gestionSeleccionada)
            ->get();

        return view('inscripciones.show', compact('curso', 'inscripciones', 'gestionSeleccionada'));
    }

    public function create(Curso $curso)
    {
        $gestion = date('Y');

        $idsYaInscritos = Inscripcion::where('gestion', $gestion)
            ->pluck('id_estudiantes');

        $estudiantesDisponibles = Estudiante::with('persona')
            ->whereNotIn('id_estudiantes', $idsYaInscritos)
            ->get();

        return view('inscripciones.create', compact('curso', 'estudiantesDisponibles', 'gestion'));
    }

    public function store(Request $request, Curso $curso)
    {
        $datos = $request->validate([
            'gestion' => ['required', 'string', 'max:10'],
            'estudiantes' => ['required', 'array', 'min:1'],
            'estudiantes.*' => ['exists:estudiantes,id_estudiantes'],
        ], [
            'estudiantes.required' => 'Selecciona al menos un estudiante.',
        ]);

        DB::transaction(function () use ($datos, $curso) {
            foreach ($datos['estudiantes'] as $idEstudiante) {
                Inscripcion::create([
                    'id_estudiantes' => $idEstudiante,
                    'id_cursos' => $curso->id_cursos,
                    'gestion' => $datos['gestion'],
                ]);
            }
        });

        return redirect()->route('inscripciones.show', ['curso' => $curso, 'gestion' => $datos['gestion']])
            ->with('exito', count($datos['estudiantes']) . ' estudiante(s) inscrito(s) correctamente.');
    }

    public function destroy(Inscripcion $inscripcion)
    {
        $curso = $inscripcion->curso;
        $gestion = $inscripcion->gestion;
        $inscripcion->delete();

        return redirect()->route('inscripciones.show', ['curso' => $curso, 'gestion' => $gestion])
            ->with('exito', 'Inscripción eliminada.');
    }
}