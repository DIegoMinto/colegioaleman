<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MisBoletinesController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        abort_unless($usuario->role->nombre === 'Estudiante', 403);

        $estudiante = $usuario->persona->estudiante;

        abort_if(!$estudiante, 404, 'No se encontró un registro de estudiante asociado a este usuario.');

        $inscripciones = $estudiante->inscripciones()
            ->with('curso')
            ->orderByDesc('gestion')
            ->get()
            ->groupBy('gestion');

        return view('mis-boletines.index', compact('inscripciones'));
    }
}