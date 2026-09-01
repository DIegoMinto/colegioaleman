<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Trimestre;

class CalificacionesController extends Controller
{
    public function index()
    {
        $gestionActual = date('Y');

        $trimestres = Trimestre::where('gestion', $gestionActual)
            ->orderBy('orden')
            ->get();

        return view('calificaciones.trimestres', compact('trimestres'));
    }

    public function cursos(Trimestre $trimestre)
    {
        $rol = auth()->user()->role->nombre;

        $query = Asignacion::with('curso', 'materia', 'docente.persona')
            ->where('gestion', $trimestre->gestion);

        if ($rol === 'Profesor') {
            $docente = auth()->user()->persona->docente;
            $query->where('id_docentes', $docente->id_docentes);
        }

        $asignaciones = $query->get();

        return view('calificaciones.cursos', compact('trimestre', 'asignaciones'));
    }
}