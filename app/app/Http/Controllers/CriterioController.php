<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Criterio;
use App\Models\Evaluacion;
use App\Models\Trimestre;
use Illuminate\Http\Request;

class CriterioController extends Controller
{
    private const DIMENSIONES = [
        'Ser' => ['peso' => 10, 'columnas' => 5],
        'Saber' => ['peso' => 45, 'columnas' => 11],
        'Hacer' => ['peso' => 40, 'columnas' => 11],
        'Decidir' => ['peso' => 5, 'columnas' => 1],
    ];

    public function index(Asignacion $asignacion, Trimestre $trimestre)
    {
        $evaluaciones = collect(self::DIMENSIONES)->map(function ($config, $dimension) use ($asignacion, $trimestre) {
            $evaluacion = Evaluacion::firstOrCreate(
                [
                    'id_asignaciones' => $asignacion->id_asignaciones,
                    'id_trimestres' => $trimestre->id_trimestres,
                    'tipo' => $dimension,
                ],
                ['nombre' => $dimension, 'porcentaje' => $config['peso']]
            );

            if ($evaluacion->criterios()->count() === 0) {
                if ($dimension === 'Decidir') {
                    Criterio::create([
                        'id_evaluaciones' => $evaluacion->id_evaluaciones,
                        'nombre' => 'Autoevaluación',
                        'orden' => 1,
                        'puntaje_maximo' => $config['peso'],
                    ]);
                } else {
                    for ($i = 1; $i <= $config['columnas']; $i++) {
                        Criterio::create([
                            'id_evaluaciones' => $evaluacion->id_evaluaciones,
                            'nombre' => "Actividad $i",
                            'orden' => $i,
                            'puntaje_maximo' => $config['peso'],
                        ]);
                    }
                }
            }

            return $evaluacion->load('criterios');
        });

        return view('criterios.index', compact('asignacion', 'trimestre', 'evaluaciones'));
    }

    public function store(Request $request, Evaluacion $evaluacion)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
        ]);

        $siguienteOrden = $evaluacion->criterios()->max('orden') + 1;

        Criterio::create([
            'id_evaluaciones' => $evaluacion->id_evaluaciones,
            'nombre' => $datos['nombre'],
            'orden' => $siguienteOrden,
            'puntaje_maximo' => $evaluacion->porcentaje,
        ]);

        return back()->with('exito', 'Columna agregada correctamente.');
    }

    public function destroy(Criterio $criterio)
    {
        if ($criterio->evaluacion->tipo === 'Decidir') {
            return back()->withErrors(['error' => 'La columna de Autoevaluación no puede eliminarse.']);
        }

        $criterio->delete();

        return back()->with('exito', 'Columna eliminada.');
    }

    public function update(Request $request, Criterio $criterio)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
        ]);

        $criterio->update($datos);

        return back()->with('exito', 'Nombre actualizado.');
    }
}