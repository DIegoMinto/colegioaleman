<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaPersonaUsuario;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    use GestionaPersonaUsuario;

    public function index()
    {
        $estudiantes = Estudiante::with('persona.usuario')->get();
        return view('estudiantes.index', compact('estudiantes'));
    }

    public function edit(Estudiante $estudiante)
    {
        $estudiante->load('persona.usuario');
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $this->actualizarPersonaUsuario($request, $estudiante->persona, $estudiante->persona->usuario);
        return redirect()->route('estudiantes.index')->with('exito', 'Estudiante actualizado correctamente.');
    }

    public function toggleEstado(Estudiante $estudiante)
    {
        $this->alternarEstado($estudiante->persona->usuario);
        return back()->with('exito', 'Estado actualizado.');
    }
}