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
        $esEdicionPermitida = $trimestre->estaActivo();
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

        $diasAsistencia = \App\Models\DiaAsistencia::where('id_asignaciones', $asignacion->id_asignaciones)
            ->where('id_trimestres', $trimestre->id_trimestres)
            ->orderBy('orden')
            ->get();

        if ($diasAsistencia->isEmpty()) {
            for ($i = 1; $i <= 19; $i++) {
                \App\Models\DiaAsistencia::create([
                    'id_asignaciones' => $asignacion->id_asignaciones,
                    'id_trimestres' => $trimestre->id_trimestres,
                    'fecha' => null,
                    'orden' => $i,
                ]);
            }
            $diasAsistencia = \App\Models\DiaAsistencia::where('id_asignaciones', $asignacion->id_asignaciones)
                ->where('id_trimestres', $trimestre->id_trimestres)
                ->orderBy('orden')
                ->get();
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

        $idsDias = $diasAsistencia->pluck('id_dias_asistencia');

        $asistencias = \App\Models\Asistencia::whereIn('id_dias_asistencia', $idsDias)
            ->get()
            ->groupBy('id_estudiantes')
            ->map(fn($grupo) => $grupo->keyBy('id_dias_asistencia'));

        $conteoAsistencias = [];
        foreach ($estudiantes as $estudiante) {
            $marcas = $asistencias[$estudiante->id_estudiantes] ?? collect();
            $conteoAsistencias[$estudiante->id_estudiantes] = $marcas->where('estado', 'presente')->count();
        }

        $irregularidades = PlanillaValidator::evaluarPlanilla($estudiantes, $evaluaciones, $calificaciones);

        return view('planillas.show', compact(
            'asignacion',
            'trimestre',
            'evaluaciones',
            'estudiantes',
            'calificaciones',
            'irregularidades',
            'esEdicionPermitida',
            'diasAsistencia',
            'asistencias',
            'conteoAsistencias'
        ));
    }

    public function store(Request $request, Asignacion $asignacion, Trimestre $trimestre)
    {
        if (!$trimestre->estaActivo()) {
            return back()->with('error', 'El periodo de calificaciones para este trimestre ha sido cerrado por la administración.');
        }
        $datos = $request->validate([
            'notas' => ['sometimes', 'array'],
            'notas.*.*' => ['nullable', 'numeric', 'min:0'],
            'nombres_criterios' => ['sometimes', 'array'],
            'nombres_criterios.*' => ['nullable', 'string', 'max:255'],
            'asistencia' => ['sometimes', 'array'],
            'asistencia.*.*' => ['nullable', 'boolean'],
            'fechas_dias' => ['sometimes', 'array'],
            'fechas_dias.*' => ['nullable', 'date'],
        ]);

        if (isset($datos['nombres_criterios'])) {
            foreach ($datos['nombres_criterios'] as $idCriterio => $nombre) {
                \App\Models\Criterio::where('id_criterios', $idCriterio)
                    ->update(['nombre' => trim($nombre)]);
            }
        }

        if (isset($datos['fechas_dias'])) {
            foreach ($datos['fechas_dias'] as $idDia => $fecha) {
                \App\Models\DiaAsistencia::where('id_dias_asistencia', $idDia)
                    ->update(['fecha' => $fecha ?: null]);
            }
        }

        DB::transaction(function () use ($datos) {
            if (isset($datos['notas'])) {
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
            }

            if (isset($datos['asistencia'])) {
                foreach ($datos['asistencia'] as $idEstudiante => $marcasPorDia) {
                    foreach ($marcasPorDia as $idDia => $presente) {
                        \App\Models\Asistencia::updateOrCreate(
                            ['id_dias_asistencia' => $idDia, 'id_estudiantes' => $idEstudiante],
                            ['estado' => $presente ? 'presente' : 'ausente']
                        );
                    }
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

    public function centralizadorCurso(Trimestre $trimestre, \App\Models\Curso $curso)
    {
        $asignaciones = Asignacion::with('materia')
            ->where('id_cursos', $curso->id_cursos)
            ->where('gestion', $trimestre->gestion)
            ->get()
            ->sortBy(fn($a) => $a->materia->nombre)
            ->values();

        $estudiantes = $curso->inscripciones()
            ->with('estudiante.persona')
            ->where('gestion', $trimestre->gestion)
            ->get()
            ->pluck('estudiante')
            ->sortBy(fn($e) => $e->persona->apellido_p . $e->persona->apellido_m . $e->persona->nombres)
            ->values();

        $promedios = [];
        foreach ($estudiantes as $estudiante) {
            foreach ($asignaciones as $asignacion) {
                $promedios[$estudiante->id_estudiantes][$asignacion->id_asignaciones] =
                    $this->calcularPromedioFinal($asignacion, $trimestre, $estudiante);
            }
        }

        $promedioGeneral = [];
        foreach ($estudiantes as $estudiante) {
            $notas = collect($promedios[$estudiante->id_estudiantes])->filter(fn($n) => $n !== null);
            $promedioGeneral[$estudiante->id_estudiantes] = $notas->isNotEmpty() ? round($notas->avg()) : null;
        }

        $ordinales = [1 => '1er', 2 => '2do', 3 => '3er', 4 => '4to'];
        $trimestreCorto = $ordinales[$trimestre->orden] ?? $trimestre->nombres;

        return view('planillas.centralizador_curso', compact(
            'curso',
            'trimestre',
            'trimestreCorto',
            'asignaciones',
            'estudiantes',
            'promedios',
            'promedioGeneral'
        ));
    }

    public function pdfCentralizadorCurso(Trimestre $trimestre, \App\Models\Curso $curso)
    {
        $asignaciones = Asignacion::with('materia')
            ->where('id_cursos', $curso->id_cursos)
            ->where('gestion', $trimestre->gestion)
            ->get()
            ->sortBy(fn($a) => $a->materia->nombre)
            ->values();

        $estudiantes = $curso->inscripciones()
            ->with('estudiante.persona')
            ->where('gestion', $trimestre->gestion)
            ->get()
            ->pluck('estudiante')
            ->sortBy(fn($e) => $e->persona->apellido_p . $e->persona->apellido_m . $e->persona->nombres)
            ->values();

        $promedios = [];
        foreach ($estudiantes as $estudiante) {
            foreach ($asignaciones as $asignacion) {
                $promedios[$estudiante->id_estudiantes][$asignacion->id_asignaciones] =
                    $this->calcularPromedioFinal($asignacion, $trimestre, $estudiante);
            }
        }

        $promedioGeneral = [];
        foreach ($estudiantes as $estudiante) {
            $notas = collect($promedios[$estudiante->id_estudiantes])->filter(fn($n) => $n !== null);
            $promedioGeneral[$estudiante->id_estudiantes] = $notas->isNotEmpty() ? round($notas->avg()) : null;
        }

        $ordinales = [1 => '1er', 2 => '2do', 3 => '3er', 4 => '4to'];
        $trimestreCorto = $ordinales[$trimestre->orden] ?? $trimestre->nombres;

        $pdf = Pdf::loadView('pdf.centralizador_curso', compact(
            'curso',
            'trimestre',
            'trimestreCorto',
            'asignaciones',
            'estudiantes',
            'promedios',
            'promedioGeneral'
        ));

        return $pdf->setPaper('legal', 'landscape')
            ->stream('Centralizador-' . $curso->nombre . $curso->paralelo . '-' . $trimestreCorto . 'Trim.pdf');
    }

    private function calcularPromedioFinal(Asignacion $asignacion, Trimestre $trimestre, Estudiante $estudiante): ?float
    {
        $evaluaciones = $asignacion->evaluaciones()
            ->where('id_trimestres', $trimestre->id_trimestres)
            ->with('criterios')
            ->get();

        if ($evaluaciones->isEmpty()) {
            return null;
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

        return $pdf->setPaper('letter', 'portrait')->stream('Centralizador-' . $asignacion->curso->nombre . '.pdf');
    }

    public function pdfPlanilla(Asignacion $asignacion, Trimestre $trimestre, Request $request)
    {
        $modo = $request->query('modo', 'llena');
        if (!in_array($modo, ['blanco', 'llena'])) {
            $modo = 'llena';
        }

        $evaluaciones = $asignacion->evaluaciones()
            ->where('id_trimestres', $trimestre->id_trimestres)
            ->with('criterios')
            ->get();

        if ($modo === 'blanco') {
            $cantidadesPorDefecto = [
                'Ser' => 2,
                'Saber' => 5,
                'Hacer' => 5,
                'Decidir' => 2,
            ];

            foreach ($evaluaciones as $evaluacion) {
                $cantidad = $cantidadesPorDefecto[$evaluacion->tipo] ?? 3;
                $criteriosFicticios = collect();

                for ($i = 0; $i < $cantidad; $i++) {
                    $criteriosFicticios->push((object) [
                        'id_criterios' => 'temp_' . $evaluacion->id_evaluaciones . '_' . $i,
                        'nombre' => ''
                    ]);
                }
                $evaluacion->setRelation('criterios', $criteriosFicticios);
            }

            $calificaciones = collect();
            $conteoAsistencias = [];
        } else {
            foreach ($evaluaciones as $evaluacion) {
                $criteriosValidos = $evaluacion->criterios->filter(function ($criterio) {
                    return !is_null($criterio->nombre) && trim($criterio->nombre) !== '';
                })->values();

                $evaluacion->setRelation('criterios', $criteriosValidos);
            }

            $idsCriterios = $evaluaciones->flatMap->criterios->pluck('id_criterios');
            $calificaciones = \App\Models\Calificacion::whereIn('id_criterios', $idsCriterios)
                ->get()
                ->groupBy('id_estudiantes')
                ->map(fn($grupo) => $grupo->keyBy('id_criterios'));
        }

        $estudiantes = $asignacion->curso->inscripciones()
            ->with('estudiante.persona')
            ->where('gestion', $asignacion->gestion)
            ->get()
            ->pluck('estudiante');

        $diasAsistencia = \App\Models\DiaAsistencia::where('id_asignaciones', $asignacion->id_asignaciones)
            ->where('id_trimestres', $trimestre->id_trimestres)
            ->orderBy('orden')
            ->get();

        if ($modo !== 'blanco') {
            $idsDias = $diasAsistencia->pluck('id_dias_asistencia');
            $asistenciasRaw = \App\Models\Asistencia::whereIn('id_dias_asistencia', $idsDias)
                ->get()
                ->groupBy('id_estudiantes');

            $conteoAsistencias = [];
            foreach ($estudiantes as $estudiante) {
                $marcas = $asistenciasRaw[$estudiante->id_estudiantes] ?? collect();
                $conteoAsistencias[$estudiante->id_estudiantes] = $marcas->where('estado', 'presente')->count();
            }
        }

        $pdf = Pdf::loadView('pdf.planilla', compact(
            'asignacion',
            'trimestre',
            'evaluaciones',
            'estudiantes',
            'calificaciones',
            'diasAsistencia',
            'conteoAsistencias',
            'modo'
        ));

        $nombreArchivo = 'Planilla-' . $asignacion->curso->nombre . '-' . ($modo === 'blanco' ? 'Vacia' : 'Con-Notas') . '.pdf';

        return $pdf->setPaper('letter', 'landscape')->stream($nombreArchivo);
    }

    public function exportarPdf(Asignacion $asignacion, Trimestre $trimestre, Request $request)
    {
        return $this->pdfPlanilla($asignacion, $trimestre, $request);
    }
}