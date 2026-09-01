<?php

namespace App\Http\Controllers;

use App\Models\Trimestre;
use Illuminate\Http\Request;

class TrimestreController extends Controller
{
    public function index()
    {
        $trimestres = Trimestre::orderBy('gestion', 'desc')->orderBy('orden')->get();
        return view('trimestres.index', compact('trimestres'));
    }

    public function create()
    {
        return view('trimestres.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombres' => ['required', 'string', 'max:50'],
            'orden' => ['required', 'integer', 'between:1,3'],
            'gestion' => ['required', 'string', 'max:10'],
        ]);

        Trimestre::create($datos);

        return redirect()->route('trimestres.index')->with('exito', 'Trimestre registrado correctamente.');
    }

    public function edit(Trimestre $trimestre)
    {
        return view('trimestres.edit', compact('trimestre'));
    }

    public function update(Request $request, Trimestre $trimestre)
    {
        $datos = $request->validate([
            'nombres' => ['required', 'string', 'max:50'],
            'orden' => ['required', 'integer', 'between:1,3'],
            'gestion' => ['required', 'string', 'max:10'],
        ]);

        $trimestre->update($datos);

        return redirect()->route('trimestres.index')->with('exito', 'Trimestre actualizado correctamente.');
    }

    public function destroy(Trimestre $trimestre)
    {
        $trimestre->delete();
        return redirect()->route('trimestres.index')->with('exito', 'Trimestre eliminado correctamente.');
    }
}