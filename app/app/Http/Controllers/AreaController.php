<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::orderBy('nombre')->get();
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
        ]);

        Area::create($datos);

        return redirect()->route('areas.index')->with('exito', 'Area registrada correctamente.');
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
        ]);

        $area->update($datos);

        return redirect()->route('areass.index')->with('exito', 'Area actualizada correctamente.');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('exito', 'Area eliminada correctamente.');
    }
}