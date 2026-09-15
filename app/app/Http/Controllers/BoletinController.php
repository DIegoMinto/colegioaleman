<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Evaluacion;
use App\Models\Inscripcion;
use App\Models\Trimestre;
use App\Models\Curso;

class BoletinController extends Controller
{
    public function pdf(Inscripcion $inscripcion, $trimestre)
    {
        $data = $this->buildBoletinData($inscripcion, $trimestre);

        return view('pdf.boletin', $data);
    }

    public function show(Inscripcion $inscripcion, $trimestre)
    {
        $data = $this->buildBoletinData($inscripcion, $trimestre);

        return view('boletines.show', $data);
    }

    public function pdfCurso(Curso $curso, $gestion, $trimestre)
    {
        abort_unless(
            in_array(auth()->user()->role->nombre, ['Administrador', 'Profesor']),
            403
        );
        $inscripciones = Inscripcion::with(['estudiante.persona'])
            ->where('id_cursos', $curso->id_cursos)
            ->where('gestion', $gestion)
            ->join('estudiantes', 'inscripciones.id_estudiantes', '=', 'estudiantes.id_estudiantes')
            ->join('personas', 'estudiantes.id_personas', '=', 'personas.id_personas')
            ->orderBy('personas.apellido_p')
            ->orderBy('personas.apellido_m')
            ->orderBy('personas.nombres')
            ->select('inscripciones.*')
            ->get();

        $boletines = [];

        foreach ($inscripciones as $inscripcion) {
            $boletines[] = $this->buildBoletinData($inscripcion, $trimestre);
        }

        return view('pdf.boletin-curso', [
            'boletines' => $boletines,
            'curso' => $curso,
            'trimestre' => $trimestre,
            'gestion' => $gestion,
        ]);
    }

    private function buildBoletinData(Inscripcion $inscripcion, $trimestre): array
    {
        $usuario = auth()->user();

        if ($usuario->role->nombre === 'Estudiante') {
            $estudiante = $usuario->persona->estudiante;

            abort_unless(
                $estudiante && $inscripcion->id_estudiantes === $estudiante->id_estudiantes,
                403,
                'No tienes permiso para ver este boletín.'
            );
        }

        $inscripcion->load(['estudiante.persona', 'curso']);

        $estudiante = $inscripcion->estudiante;
        $curso = $inscripcion->curso;
        $gestion = $inscripcion->gestion;

        $asignaciones = Asignacion::with('materia')
            ->where('id_cursos', $curso->id_cursos)
            ->where('gestion', $gestion)
            ->get();

        $curso->setRelation('asignaciones', $asignaciones);

        $trimestres = Trimestre::where('gestion', $gestion)
            ->where('orden', '<=', $trimestre)
            ->orderBy('orden')
            ->get();

        $notas = [];
        $promedios = [];
        $promediosLiteral = [];

        if ($asignaciones->isNotEmpty() && $trimestres->isNotEmpty()) {

            $evaluaciones = Evaluacion::whereIn('id_asignaciones', $asignaciones->pluck('id_asignaciones'))
                ->whereIn('id_trimestres', $trimestres->pluck('id_trimestres'))
                ->with([
                    'criterios.calificaciones' => function ($query) use ($estudiante) {
                        $query->where('id_estudiantes', $estudiante->id_estudiantes);
                    }
                ])
                ->get()
                ->groupBy(['id_asignaciones', 'id_trimestres']);

            foreach ($asignaciones as $asignacion) {
                $notasPorTrimestre = [1 => '-', 2 => '-', 3 => '-'];

                foreach ($trimestres as $trimestreObj) {
                    $orden = $trimestreObj->orden;

                    $evaluacionesAsigTrim = $evaluaciones
                        ->get($asignacion->id_asignaciones, collect())
                        ->get($trimestreObj->id_trimestres, collect());

                    $notaTrimestre = 0;
                    $tieneAlgunaNota = false;

                    foreach ($evaluacionesAsigTrim as $evaluacion) {
                        $calificaciones = $evaluacion->criterios->flatMap->calificaciones;

                        if ($calificaciones->isNotEmpty()) {
                            $notaTrimestre += $calificaciones->avg('nota');
                            $tieneAlgunaNota = true;
                        }
                    }

                    $notasPorTrimestre[$orden] = $tieneAlgunaNota ? round($notaTrimestre) : '-';
                }

                $notas[$asignacion->id_asignaciones] = $notasPorTrimestre;

                $notasNumericas = array_filter($notasPorTrimestre, 'is_numeric');

                if (count($notasNumericas) > 0) {
                    $promedio = round(array_sum($notasNumericas) / count($notasNumericas));
                    $promedios[$asignacion->id_asignaciones] = $promedio;
                    $promediosLiteral[$asignacion->id_asignaciones] = $this->numeroALetras($promedio);
                } else {
                    $promedios[$asignacion->id_asignaciones] = '-';
                    $promediosLiteral[$asignacion->id_asignaciones] = '-';
                }
            }
        }

        $notasTrimestreActual = array_filter(array_map(function ($n) use ($trimestre) {
            return $n[$trimestre] ?? '-';
        }, $notas), 'is_numeric');

        $promedioGeneral = count($notasTrimestreActual) > 0
            ? round(array_sum($notasTrimestreActual) / count($notasTrimestreActual))
            : '-';

        $numeroLista = Inscripcion::where('id_cursos', $curso->id_cursos)
            ->where('gestion', $gestion)
            ->join('estudiantes', 'inscripciones.id_estudiantes', '=', 'estudiantes.id_estudiantes')
            ->join('personas', 'estudiantes.id_personas', '=', 'personas.id_personas')
            ->orderBy('personas.apellido_p')
            ->orderBy('personas.apellido_m')
            ->orderBy('personas.nombres')
            ->pluck('inscripciones.id_estudiantes')
            ->search($estudiante->id_estudiantes);

        $numeroLista = $numeroLista !== false ? $numeroLista + 1 : '-';

        return compact(
            'inscripcion',
            'estudiante',
            'curso',
            'trimestre',
            'notas',
            'promedios',
            'promediosLiteral',
            'promedioGeneral',
            'numeroLista'
        );
    }
    private function numeroALetras(int $n): string
    {
        if ($n <= 0) {
            return 'CERO';
        }
        if ($n >= 100) {
            return 'CIEN';
        }

        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];

        $especiales10a29 = [
            10 => 'DIEZ',
            11 => 'ONCE',
            12 => 'DOCE',
            13 => 'TRECE',
            14 => 'CATORCE',
            15 => 'QUINCE',
            16 => 'DIECISÉIS',
            17 => 'DIECISIETE',
            18 => 'DIECIOCHO',
            19 => 'DIECINUEVE',
            20 => 'VEINTE',
            21 => 'VEINTIUNO',
            22 => 'VEINTIDÓS',
            23 => 'VEINTITRÉS',
            24 => 'VEINTICUATRO',
            25 => 'VEINTICINCO',
            26 => 'VEINTISÉIS',
            27 => 'VEINTISIETE',
            28 => 'VEINTIOCHO',
            29 => 'VEINTINUEVE',
        ];

        if ($n < 10) {
            return $unidades[$n];
        }

        if ($n <= 29) {
            return $especiales10a29[$n];
        }

        $decenas = [
            30 => 'TREINTA',
            40 => 'CUARENTA',
            50 => 'CINCUENTA',
            60 => 'SESENTA',
            70 => 'SETENTA',
            80 => 'OCHENTA',
            90 => 'NOVENTA',
        ];

        $decena = intdiv($n, 10) * 10;
        $unidad = $n % 10;

        if ($unidad === 0) {
            return $decenas[$decena];
        }

        return $decenas[$decena] . ' Y ' . $unidades[$unidad];
    }
}