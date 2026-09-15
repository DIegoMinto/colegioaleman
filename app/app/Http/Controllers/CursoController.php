<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::orderBy('nivel')->orderBy('paralelo')->get();
        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('cursos.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'nivel' => ['required', 'string', 'max:50'],
            'paralelo' => ['required', 'string', 'max:5'],
        ]);

        Curso::create($datos);

        return redirect()->route('cursos.index')->with('exito', 'Curso registrado correctamente.');
    }

    public function edit(Curso $curso)
    {
        return view('cursos.edit', compact('curso'));
    }

    public function update(Request $request, Curso $curso)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'nivel' => ['required', 'string', 'max:50'],
            'paralelo' => ['required', 'string', 'max:5'],
        ]);

        $curso->update($datos);

        return redirect()->route('cursos.index')->with('exito', 'Curso actualizado correctamente.');
    }

    public function destroy(Curso $curso)
    {
        try {
            $curso->delete();

            return redirect()
                ->route('cursos.index')
                ->with('exito', 'Curso eliminado correctamente.');

        } catch (QueryException $e) {
            return redirect()
                ->route('cursos.index')
                ->with('error', 'No se puede eliminar el curso porque tiene registros asociados (estudiantes, asignaciones, materias, etc.).');

        } catch (\Exception $e) {
            return redirect()
                ->route('cursos.index')
                ->with('error', 'Ocurrió un error inesperado al intentar eliminar el curso.');
        }
    }
}