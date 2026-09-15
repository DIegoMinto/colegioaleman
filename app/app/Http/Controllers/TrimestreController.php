<?php

namespace App\Http\Controllers;

use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
        $datos = $this->validarDatos($request);

        DB::transaction(function () use ($datos) {
            if (!empty($datos['activo'])) {
                Trimestre::where('gestion', $datos['gestion'])->update(['activo' => false]);
            }

            Trimestre::create($datos);
        });

        return redirect()->route('trimestres.index')->with('exito', 'Trimestre registrado correctamente.');
    }

    public function edit(Trimestre $trimestre)
    {
        return view('trimestres.edit', compact('trimestre'));
    }

    public function update(Request $request, Trimestre $trimestre)
    {
        $datos = $this->validarDatos($request);

        DB::transaction(function () use ($datos, $trimestre) {
            if (!empty($datos['activo'])) {
                Trimestre::where('gestion', $datos['gestion'])
                    ->where('id_trimestres', '!=', $trimestre->id_trimestres)
                    ->update(['activo' => false]);
            }

            $trimestre->update($datos);
        });

        return redirect()->route('trimestres.index')->with('exito', 'Trimestre actualizado correctamente.');
    }

    public function destroy(Trimestre $trimestre)
    {
        try {
            $trimestre->delete();

            return redirect()
                ->route('trimestres.index')
                ->with('exito', 'Trimestre eliminado correctamente.');

        } catch (QueryException $e) {
            return redirect()
                ->route('trimestres.index')
                ->with('error', 'No se puede eliminar el trimestre porque tiene registros asociados (notas, actividades, etc.).');
        } catch (\Exception $e) {
            return redirect()
                ->route('trimestres.index')
                ->with('error', 'Ocurrió un error inesperado al intentar eliminar el trimestre.');
        }
    }

    public function toggleEstado(Trimestre $trimestre)
    {
        DB::transaction(function () use ($trimestre) {
            $nuevoEstado = !$trimestre->activo;

            if ($nuevoEstado) {
                Trimestre::where('gestion', $trimestre->gestion)
                    ->where('id_trimestres', '!=', $trimestre->id_trimestres)
                    ->update(['activo' => false]);
            }

            $trimestre->update(['activo' => $nuevoEstado]);
        });

        $mensaje = $trimestre->activo ? 'El trimestre ha sido ABIERTO para edición.' : 'El trimestre ha sido CERRADO.';
        return back()->with('exito', $mensaje);
    }

    private function validarDatos(Request $request): array
    {
        $datos = $request->validate([
            'nombres' => ['required', 'string', 'max:50'],
            'orden' => ['required', 'integer', 'between:1,3'],
            'gestion' => ['required', 'string', 'max:10'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'activo' => ['nullable', 'boolean'],
        ], [
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ]);

        $datos['activo'] = $request->has('activo');

        return $datos;
    }
}