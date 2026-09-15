<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('area')->orderBy('nombre')->get();
        return view('materias.index', compact('materias'));
    }

    public function create()
    {
        $areas = Area::orderBy('nombre')->get();
        return view('materias.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'id_areas' => ['required', 'exists:areas,id_areas'],
        ]);

        Materia::create($datos);

        return redirect()->route('materias.index')->with('exito', 'Materia registrada correctamente.');
    }

    public function edit(Materia $materia)
    {
        $areas = Area::orderBy('nombre')->get();
        return view('materias.edit', compact('materia', 'areas'));
    }

    public function update(Request $request, Materia $materia)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'id_areas' => ['required', 'exists:areas,id_areas'],
        ]);

        $materia->update($datos);

        return redirect()->route('materias.index')->with('exito', 'Materia actualizada correctamente.');
    }

    public function destroy(Materia $materia)
    {
        try {
            $materia->delete();

            return redirect()
                ->route('materias.index')
                ->with('exito', 'Materia eliminada correctamente.');

        } catch (QueryException $e) {
            return redirect()
                ->route('materias.index')
                ->with('error', 'No se puede eliminar la materia porque tiene registros asociados (asignaciones, notas, actividades, etc.).');

        } catch (\Exception $e) {
            return redirect()
                ->route('materias.index')
                ->with('error', 'Ocurrió un error inesperado al intentar eliminar la materia.');
        }
    }
}