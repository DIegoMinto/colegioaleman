<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class InscripcionImportController extends Controller
{
    private const SESSION_KEY_PREFIX = 'importacion_inscripcion_pdf_curso_';

    public function form(Curso $curso)
    {
        return view('inscripciones.importar', compact('curso'));
    }

    public function previsualizar(Request $request, Curso $curso)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf',
            'gestion' => 'required|integer|min:2000',
        ]);

        $gestion = $request->input('gestion');
        $rutaTemporal = $request->file('archivo')->store('importaciones_tmp');
        $filas = $this->extraerFilasDelPdf(Storage::path($rutaTemporal));

        $preview = [];
        foreach ($filas as $fila) {
            $carnet = $fila['carnet'];
            $esEfectivo = stripos($fila['matricula'], 'EFECTIVO') !== false;

            if (!$esEfectivo) {
                $estado = 'omitido';
            } else {
                $persona = Persona::where('ci', $carnet)->first();
                $estudiante = $persona?->estudiante;

                if (!$estudiante) {
                    $estado = 'no_existe';
                } else {
                    $yaInscrito = Inscripcion::where('id_estudiantes', $estudiante->id_estudiantes)
                        ->where('gestion', $gestion)
                        ->exists();

                    $estado = $yaInscrito ? 'ya_inscrito' : 'por_inscribir';
                }
            }

            $preview[] = [
                'numero_fila' => $fila['numero_fila'],
                'carnet' => $carnet,
                'nombre_completo' => $fila['nombre_completo'],
                'matricula' => $fila['matricula'],
                'estado' => $estado,
            ];
        }

        session([self::SESSION_KEY_PREFIX . $curso->id_cursos => $rutaTemporal]);

        return view('inscripciones.importar-preview', compact('curso', 'gestion', 'preview'));
    }

    public function confirmar(Request $request, Curso $curso)
    {
        $gestion = $request->input('gestion');
        $rutaTemporal = session(self::SESSION_KEY_PREFIX . $curso->id_cursos);

        if (!$rutaTemporal || !Storage::exists($rutaTemporal)) {
            return redirect()
                ->route('inscripciones.importar.form', $curso)
                ->with('erroresImportacion', ['La sesión de importación expiró, vuelve a subir el archivo.']);
        }

        $filas = $this->extraerFilasDelPdf(Storage::path($rutaTemporal));

        $inscritos = 0;
        $omitidos = 0;
        $errores = [];

        foreach ($filas as $fila) {
            $numeroFila = $fila['numero_fila'];
            $carnet = $fila['carnet'];

            try {
                if (stripos($fila['matricula'], 'EFECTIVO') === false) {
                    $omitidos++;
                    continue;
                }

                $persona = Persona::where('ci', $carnet)->first();
                $estudiante = $persona?->estudiante;

                if (!$estudiante) {
                    throw new \Exception("No existe un estudiante con CI {$carnet}. Impórtalo primero desde 'Estudiantes'.");
                }

                DB::transaction(function () use ($estudiante, $curso, $gestion, &$inscritos) {
                    $yaInscrito = Inscripcion::where('id_estudiantes', $estudiante->id_estudiantes)
                        ->where('gestion', $gestion)
                        ->exists();

                    if ($yaInscrito) {
                        return;
                    }

                    Inscripcion::create([
                        'id_estudiantes' => $estudiante->id_estudiantes,
                        'id_cursos' => $curso->id_cursos,
                        'gestion' => $gestion,
                    ]);

                    $inscritos++;
                });
            } catch (\Throwable $e) {
                $errores[] = "Fila {$numeroFila} (Carnet {$carnet}): " . $e->getMessage();
            }
        }

        Storage::delete($rutaTemporal);
        session()->forget(self::SESSION_KEY_PREFIX . $curso->id_cursos);

        return redirect()
            ->route('inscripciones.show', ['curso' => $curso, 'gestion' => $gestion])
            ->with('exito', "Importación finalizada: {$inscritos} estudiantes inscritos, {$omitidos} omitidos (retirados/trasladados).")
            ->with('erroresImportacion', $errores);
    }

    private function extraerFilasDelPdf(string $rutaAbsolutaPdf): array
    {
        $rutaScript = base_path('resources/scripts/extraer_rude.py');

        $resultado = Process::run(['python3', $rutaScript, $rutaAbsolutaPdf]);

        if ($resultado->failed()) {
            throw new \Exception('Error al ejecutar el extractor de PDF: ' . $resultado->errorOutput());
        }

        $datos = json_decode($resultado->output(), true);

        if (isset($datos['error'])) {
            throw new \Exception('Error al leer el PDF: ' . $datos['error']);
        }

        return $datos ?? [];
    }
}