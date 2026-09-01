<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Calificacion;
use App\Models\Trimestre;
use App\Models\Estudiante;
use App\Services\PlanillaValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class PlanillaController extends Controller
{

    private const NOTA_APROBACION = 51;
    public function show(Asignacion $asignacion, Trimestre $trimestre)
    {
        $dimensiones = [
            'Ser' => ['peso' => 10, 'columnas' => 5],
            'Saber' => ['peso' => 45, 'columnas' => 11],
            'Hacer' => ['peso' => 40, 'columnas' => 11],
            'Decidir' => ['peso' => 5, 'columnas' => 1],
        ];

        foreach ($dimensiones as $dimension => $config) {
            $evaluacion = \App\Models\Evaluacion::firstOrCreate(
                [
                    'id_asignaciones' => $asignacion->id_asignaciones,
                    'id_trimestres' => $trimestre->id_trimestres,
                    'tipo' => $dimension,
                ],
                ['nombre' => $dimension, 'porcentaje' => $config['peso']]
            );

            if ($evaluacion->criterios()->count() === 0) {
                $nombre = $dimension === 'Decidir' ? 'Autoevaluación' : null;
                for ($i = 1; $i <= $config['columnas']; $i++) {
                    \App\Models\Criterio::create([
                        'id_evaluaciones' => $evaluacion->id_evaluaciones,
                        'nombre' => $nombre ?? " ",
                        'orden' => $i,
                        'puntaje_maximo' => $config['peso'],
                    ]);
                }
            }
        }

        $evaluaciones = $asignacion->evaluaciones()
            ->where('id_trimestres', $trimestre->id_trimestres)
            ->with('criterios')
            ->get();

        $estudiantes = $asignacion->curso->inscripciones()
            ->with('estudiante.persona')
            ->where('gestion', $asignacion->gestion)
            ->get()
            ->pluck('estudiante');

        $idsCriterios = $evaluaciones->flatMap->criterios->pluck('id_criterios');

        $calificaciones = \App\Models\Calificacion::whereIn('id_criterios', $idsCriterios)
            ->get()
            ->groupBy('id_estudiantes')
            ->map(fn($grupo) => $grupo->keyBy('id_criterios'));

        $irregularidades = PlanillaValidator::evaluarPlanilla($estudiantes, $evaluaciones, $calificaciones);

        return view('planillas.show', compact(
            'asignacion',
            'trimestre',
            'evaluaciones',
            'estudiantes',
            'calificaciones',
            'irregularidades'
        ));
    }

    public function store(Request $request, Asignacion $asignacion, Trimestre $trimestre)
    {
        $datos = $request->validate([
            'notas' => ['required', 'array'],
            'notas.*.*' => ['nullable', 'numeric', 'min:0'],
            'nombres_criterios' => ['sometimes', 'array'],
            'nombres_criterios.*' => ['nullable', 'string', 'max:255'],
        ]);

        if (isset($datos['nombres_criterios'])) {
            foreach ($datos['nombres_criterios'] as $idCriterio => $nombre) {
                \App\Models\Criterio::where('id_criterios', $idCriterio)
                    ->update(['nombre' => trim($nombre)]);
            }
        }

        DB::transaction(function () use ($datos) {
            foreach ($datos['notas'] as $idEstudiante => $notasPorCriterio) {
                foreach ($notasPorCriterio as $idCriterio => $nota) {
                    if ($nota === null || $nota === '')
                        continue;
                    Calificacion::updateOrCreate(
                        ['id_criterios' => $idCriterio, 'id_estudiantes' => $idEstudiante],
                        ['nota' => $nota]
                    );
                }
            }
        });

        return back()->with('exito', 'Planilla guardada correctamente.');
    }



    private function verificarPropietario(Asignacion $asignacion): void
    {
        $docente = auth()->user()->persona->docente;

        if (!$docente || $asignacion->id_docentes !== $docente->id_docentes) {
            abort(403, 'No puedes modificar las calificaciones de una asignación que no te pertenece.');
        }
    }

    public function centralizador(Asignacion $asignacion, Trimestre $trimestre)
    {
        $trimestreActual = $trimestre;

        $trimestres = Trimestre::where('gestion', $trimestreActual->gestion)
            ->where('orden', '<=', $trimestreActual->orden)
            ->orderBy('orden')
            ->get();

        $estudiantes = $asignacion->curso->inscripciones()
            ->with('estudiante.persona')
            ->where('gestion', $asignacion->gestion)
            ->get()
            ->pluck('estudiante');

        $promediosPorTrimestre = [];
        foreach ($trimestres as $tri) {
            foreach ($estudiantes as $estudiante) {
                $promediosPorTrimestre[$estudiante->id_estudiantes][$tri->id_trimestres] =
                    $this->calcularPromedioFinal($asignacion, $tri, $estudiante);
            }
        }

        $promedioAnual = [];
        foreach ($estudiantes as $estudiante) {
            $notas = collect($promediosPorTrimestre[$estudiante->id_estudiantes])->filter(fn($n) => $n !== null);
            $promedioAnual[$estudiante->id_estudiantes] = $notas->count() === $trimestres->count()
                ? round($notas->avg())
                : null;
        }

        // --- ESTADÍSTICAS POR GÉNERO ---
        $estadisticas = [
            'varones' => ['aprobados' => 0, 'reprobados' => 0],
            'mujeres' => ['aprobados' => 0, 'reprobados' => 0],
        ];

        foreach ($estudiantes as $estudiante) {
            $promedio = $promedioAnual[$estudiante->id_estudiantes];
            if ($promedio === null || $estudiante->persona->sexo === null)
                continue;

            $grupo = $estudiante->persona->sexo === 'M' ? 'varones' : 'mujeres';
            $estadisticas[$grupo][$promedio >= self::NOTA_APROBACION ? 'aprobados' : 'reprobados']++;
        }

        // --- CUADRO DE APROVECHAMIENTO (Promedio General del Curso) ---
        $aprovechamientoTrimestral = [];
        foreach ($trimestres as $tri) {
            $notasTri = collect($promediosPorTrimestre)->map(fn($e) => $e[$tri->id_trimestres] ?? null)->filter(fn($n) => $n !== null);
            $aprovechamientoTrimestral[$tri->id_trimestres] = $notasTri->isNotEmpty() ? round($notasTri->avg(), 2) : null;
        }

        $notasAnuales = collect($promedioAnual)->filter(fn($n) => $n !== null);
        $aprovechamientoAnual = $notasAnuales->isNotEmpty() ? round($notasAnuales->avg(), 2) : null;

        return view('planillas.centralizador', compact(
            'asignacion',
            'trimestres',
            'estudiantes',
            'promediosPorTrimestre',
            'promedioAnual',
            'estadisticas',
            'aprovechamientoTrimestral',
            'aprovechamientoAnual'
        ));
    }

    private function calcularPromedioFinal(Asignacion $asignacion, Trimestre $trimestre, Estudiante $estudiante): ?float
    {
        $evaluaciones = $asignacion->evaluaciones()
            ->where('id_trimestres', $trimestre->id_trimestres)
            ->with('criterios')
            ->get();

        if ($evaluaciones->isEmpty()) {
            return null; // ese trimestre nunca se llegó a cargar para esta asignación
        }

        $idsCriterios = $evaluaciones->flatMap->criterios->pluck('id_criterios');

        $calificaciones = Calificacion::whereIn('id_criterios', $idsCriterios)
            ->where('id_estudiantes', $estudiante->id_estudiantes)
            ->get()
            ->keyBy('id_criterios');

        $promediosPorDimension = [];

        foreach ($evaluaciones as $evaluacion) {
            $notas = $evaluacion->criterios
                ->map(fn($criterio) => $calificaciones[$criterio->id_criterios]->nota ?? null)
                ->filter(fn($n) => $n !== null);

            $promediosPorDimension[$evaluacion->tipo] = $notas->isNotEmpty() ? $notas->avg() : null;
        }

        $todasCompletas = collect($promediosPorDimension)->every(fn($p) => $p !== null);

        return $todasCompletas ? round(array_sum($promediosPorDimension)) : null;
    }

    public function pdfCentralizador(Asignacion $asignacion, Trimestre $trimestre)
    {
        $trimestreActual = $trimestre;

        $trimestres = Trimestre::where('gestion', $trimestreActual->gestion)
            ->where('orden', '<=', $trimestreActual->orden)
            ->orderBy('orden')
            ->get();

        $estudiantes = $asignacion->curso->inscripciones()
            ->with('estudiante.persona')
            ->where('gestion', $asignacion->gestion)
            ->get()
            ->pluck('estudiante');

        $promediosPorTrimestre = [];
        foreach ($trimestres as $tri) {
            foreach ($estudiantes as $estudiante) {
                $promediosPorTrimestre[$estudiante->id_estudiantes][$tri->id_trimestres] =
                    $this->calcularPromedioFinal($asignacion, $tri, $estudiante);
            }
        }

        $promedioAnual = [];
        foreach ($estudiantes as $estudiante) {
            $notas = collect($promediosPorTrimestre[$estudiante->id_estudiantes])->filter(fn($n) => $n !== null);
            $promedioAnual[$estudiante->id_estudiantes] = $notas->count() === $trimestres->count()
                ? round($notas->avg())
                : null;
        }

        $pdf = Pdf::loadView('pdf.centralizador', compact(
            'asignacion',
            'trimestres',
            'estudiantes',
            'promediosPorTrimestre',
            'promedioAnual'
        ));

        // Formato Carta / Portrait (Vertical)
        return $pdf->setPaper('letter', 'portrait')->stream('Centralizador-' . $asignacion->curso->nombre . '.pdf');
    }
}