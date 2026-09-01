<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaPersonaUsuario;
use App\Models\Administrativo;
use Illuminate\Http\Request;

class AdministrativoController extends Controller
{
    use GestionaPersonaUsuario;

    public function index()
    {
        $administrativos = Administrativo::with('persona.usuario')->get();
        return view('administrativos.index', compact('administrativos'));
    }

    public function edit(Administrativo $administrativo)
    {
        $administrativo->load('persona.usuario');
        return view('administrativos.edit', compact('administrativo'));
    }

    public function update(Request $request, Administrativo $administrativo)
    {
        $this->actualizarPersonaUsuario($request, $administrativo->persona, $administrativo->persona->usuario);
        return redirect()->route('administrativos.index')->with('exito', 'Administrativo actualizado correctamente.');
    }

    public function toggleEstado(Administrativo $administrativo)
    {
        $this->alternarEstado($administrativo->persona->usuario);
        return back()->with('exito', 'Estado actualizado.');
    }
}