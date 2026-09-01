<?php

namespace App\Services;

class PlanillaValidator
{
    public static function evaluarPlanilla($estudiantes, $evaluaciones, $calificaciones)
    {
        $irregularidades = [];

        foreach ($estudiantes as $estudiante) {
            $nombreEstudiante = trim($estudiante->persona->apellido_p . ' ' .
                $estudiante->persona->apellido_m . ' ' .
                $estudiante->persona->nombres);

            $notasDelEstudiante = $calificaciones[$estudiante->id_estudiantes] ?? collect();

            $criteriosSinNota = [];
            $criteriosConCero = [];
            $dimensionesIncompletas = [];

            foreach ($evaluaciones as $evaluacion) {
                $criteriosActivos = $evaluacion->criterios->filter(function ($criterio) {
                    return !empty(trim($criterio->nombre));
                });

                if ($criteriosActivos->isEmpty()) {
                    continue;
                }

                $notasIngresadasCount = 0;

                foreach ($criteriosActivos as $criterio) {
                    $calificacion = $notasDelEstudiante[$criterio->id_criterios]->nota ?? null;

                    if ($calificacion === null || $calificacion === '') {
                        $criteriosSinNota[] = $criterio->nombre . " ({$evaluacion->tipo})";
                    } else {
                        $notasIngresadasCount++;
                        if ((float) $calificacion === 0.0) {
                            $criteriosConCero[] = $criterio->nombre . " ({$evaluacion->tipo})";
                        }
                    }
                }

                if ($notasIngresadasCount < $criteriosActivos->count()) {
                    $dimensionesIncompletas[] = $evaluacion->tipo;
                }
            }

            if (!empty($criteriosSinNota) || !empty($criteriosConCero)) {
                $irregularidades[] = [
                    'estudiante_id' => $estudiante->id_estudiantes,
                    'nombre' => $nombreEstudiante,
                    'sin_nota' => $criteriosSinNota,
                    'con_cero' => $criteriosConCero,
                    'dimensiones_incompletas' => array_unique($dimensionesIncompletas),
                ];
            }
        }

        return $irregularidades;
    }
}