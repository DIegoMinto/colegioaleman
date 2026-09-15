<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Trimestre;
use App\Models\Curso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\PlanillaValidator;

class CalificacionesController extends Controller
{
    public function index(Request $request)
    {
        $gestiones = Trimestre::select('gestion')
            ->distinct()
            ->orderBy('gestion', 'desc')
            ->pluck('gestion');

        $gestionSeleccionada = $request->get('gestion', date('Y'));

        if ($gestiones->isNotEmpty() && !$gestiones->contains($gestionSeleccionada)) {
            $gestionSeleccionada = $gestiones->first();
        }

        $trimestres = Trimestre::where('gestion', $gestionSeleccionada)
            ->orderBy('orden')
            ->get();

        return view('calificaciones.trimestres', compact('trimestres', 'gestiones', 'gestionSeleccionada'));
    }

    public function cursos(Trimestre $trimestre)
    {
        $asignaciones = $this->construirAsignacionesConIrregularidades($trimestre);

        $asignacionesPorCurso = $asignaciones->groupBy(function ($asignacion) {
            return $asignacion->curso->id_cursos;
        });

        return view('calificaciones.cursos', compact('trimestre', 'asignaciones', 'asignacionesPorCurso'));
    }

    private function construirAsignacionesConIrregularidades(Trimestre $trimestre, ?int $idCurso = null)
    {
        $rol = auth()->user()->role->nombre;

        $query = Asignacion::with([
            'curso.inscripciones.estudiante.persona',
            'materia',
            'docente.persona',
            'evaluaciones' => function ($q) use ($trimestre) {
                $q->where('id_trimestres', $trimestre->id_trimestres)->with('criterios.calificaciones');
            }
        ])->where('gestion', $trimestre->gestion);

        if ($rol === 'Profesor') {
            $docente = auth()->user()->persona->docente;
            $query->where('id_docentes', $docente->id_docentes);
        }

        if ($idCurso) {
            $query->where('id_cursos', $idCurso);
        }

        $asignaciones = $query->get();

        $asignaciones->transform(function ($asignacion) {
            $estudiantes = $asignacion->curso->inscripciones->pluck('estudiante')->filter();
            $evaluaciones = $asignacion->evaluaciones;

            $calificacionesMap = collect();

            foreach ($evaluaciones as $evaluacion) {
                foreach ($evaluacion->criterios as $criterio) {
                    foreach ($criterio->calificaciones as $calificacion) {
                        $estudianteId = $calificacion->id_estudiantes;
                        $criterioId = $calificacion->id_criterios;

                        if (!$calificacionesMap->has($estudianteId)) {
                            $calificacionesMap->put($estudianteId, collect());
                        }

                        $calificacionesMap[$estudianteId]->put($criterioId, $calificacion);
                    }
                }
            }

            $asignacion->irregularidades = PlanillaValidator::evaluarPlanilla(
                $estudiantes,
                $evaluaciones,
                $calificacionesMap
            );

            return $asignacion;
        });

        return $asignaciones;
    }

    public function reporteGeneral(Trimestre $trimestre)
    {
        $asignaciones = $this->construirAsignacionesConIrregularidades($trimestre);
        $filas = $this->construirFilasReporte($asignaciones);

        return $this->generarPdfReporte($filas, $trimestre, null);
    }

    public function reportePorCurso(Trimestre $trimestre, Curso $curso)
    {
        $asignaciones = $this->construirAsignacionesConIrregularidades($trimestre, $curso->id_cursos);
        $filas = $this->construirFilasReporte($asignaciones);

        return $this->generarPdfReporte($filas, $trimestre, $curso);
    }

    private function construirFilasReporte($asignaciones): array
    {
        $filas = [];

        foreach ($asignaciones as $asignacion) {
            $irregularidades = collect($asignacion->irregularidades ?? []);

            $sinNota = 0;
            $conCero = 0;
            $dimIncompletas = 0;

            foreach ($irregularidades as $item) {
                $item = (array) $item;
                $sinNota += count($item['sin_nota'] ?? []);
                $conCero += count($item['con_cero'] ?? []);
                $dimIncompletas += count($item['dimensiones_incompletas'] ?? []);
            }

            $docentePersona = optional($asignacion->docente)->persona;

            $filas[] = [
                'curso' => ($asignacion->curso->nombre ?? '') . ' "' . ($asignacion->curso->paralelo ?? '') . '"',
                'materia' => $asignacion->materia->nombre ?? 'Sin Materia',
                'docente' => $docentePersona ? trim($docentePersona->nombres . ' ' . $docentePersona->apellido_p) : 'Sin docente',
                'estado' => $irregularidades->isNotEmpty() ? 'Con observaciones' : 'Completa',
                'estudiantes_con_observaciones' => $irregularidades->count(),
                'sin_nota' => $sinNota,
                'con_cero' => $conCero,
                'dimensiones_incompletas' => $dimIncompletas,
            ];
        }

        usort($filas, fn($a, $b) => [$a['curso'], $a['materia']] <=> [$b['curso'], $b['materia']]);

        return $filas;
    }

    private function generarPdfReporte(array $filas, Trimestre $trimestre, ?Curso $curso)
    {
        $totalMaterias = count($filas);
        $completas = collect($filas)->where('estado', 'Completa')->count();
        $conObservaciones = $totalMaterias - $completas;

        $nombreArchivo = $curso
            ? 'reporte_planillas_' . Str::slug($curso->nombre . '_' . $curso->paralelo) . '_trim' . $trimestre->orden . '.pdf'
            : 'reporte_planillas_general_trim' . $trimestre->orden . '.pdf';

        $pdf = Pdf::loadView('pdf.reporte-planillas', [
            'filas' => $filas,
            'trimestre' => $trimestre,
            'curso' => $curso,
            'totalMaterias' => $totalMaterias,
            'completas' => $completas,
            'conObservaciones' => $conObservaciones,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($nombreArchivo);
    }
}