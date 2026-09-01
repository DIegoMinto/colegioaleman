<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaPersonaUsuario;
use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    use GestionaPersonaUsuario;

    public function index()
    {
        $docentes = Docente::with('persona.usuario')->get();
        return view('docentes.index', compact('docentes'));
    }

    public function edit(Docente $docente)
    {
        $docente->load('persona.usuario');
        return view('docentes.edit', compact('docente'));
    }

    public function update(Request $request, Docente $docente)
    {
        $this->actualizarPersonaUsuario($request, $docente->persona, $docente->persona->usuario);
        return redirect()->route('docentes.index')->with('exito', 'Docente actualizado correctamente.');
    }

    public function toggleEstado(Docente $docente)
    {
        $this->alternarEstado($docente->persona->usuario);
        return back()->with('exito', 'Estado actualizado.');
    }
}