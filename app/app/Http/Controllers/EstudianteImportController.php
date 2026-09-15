<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class EstudianteImportController extends Controller
{
    private const DOMINIO_INSTITUCIONAL = 'colegioalemansucre.edu.bo';
    private const SESSION_KEY = 'importacion_pdf_estudiantes';

    private array $mesesEspanol = [
        'ene' => '01',
        'feb' => '02',
        'mar' => '03',
        'abr' => '04',
        'may' => '05',
        'jun' => '06',
        'jul' => '07',
        'ago' => '08',
        'set' => '09',
        'sep' => '09',
        'oct' => '10',
        'nov' => '11',
        'dic' => '12',
    ];

    public function form()
    {
        return view('estudiantes.importar');
    }

    public function previsualizar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf',
        ]);

        $rutaTemporal = $request->file('archivo')->store('importaciones_tmp');
        $filas = $this->extraerFilasDelPdf(Storage::path($rutaTemporal));

        $preview = [];
        foreach ($filas as $fila) {
            $esEfectivo = stripos($fila['matricula'], 'EFECTIVO') !== false;
            $existePersona = Persona::where('ci', $fila['carnet'])->exists();
            $fechaNacimiento = $this->parseFechaRude($fila['fecha_nacimiento_texto']);
            [$apellidoP, $apellidoM, $nombres] = $this->separarNombreCompleto($fila['nombre_completo']);

            $estado = !$esEfectivo ? 'omitido' : ($existePersona ? 'existente' : 'nuevo');

            $preview[] = [
                'numero_fila' => $fila['numero_fila'],
                'codigo_rude' => $fila['codigo_rude'],
                'carnet' => $fila['carnet'],
                'nombre_completo' => $fila['nombre_completo'],
                'genero' => $fila['genero'],
                'fecha_nacimiento' => $fechaNacimiento,
                'departamento' => $fila['departamento'],
                'matricula' => $fila['matricula'],
                'estado' => $estado,
                'email_proyectado' => $estado === 'nuevo'
                    ? $this->generarEmailInstitucional($nombres, $apellidoP, $apellidoM)
                    : null,
                'fecha_valida' => (bool) $fechaNacimiento,
            ];
        }

        session([self::SESSION_KEY => $rutaTemporal]);

        return view('estudiantes.importar-preview', compact('preview'));
    }

    public function confirmar(Request $request)
    {
        $rutaTemporal = session(self::SESSION_KEY);

        if (!$rutaTemporal || !Storage::exists($rutaTemporal)) {
            return redirect()
                ->route('estudiantes.importar.form')
                ->with('erroresImportacion', ['La sesión de importación expiró, vuelve a subir el archivo.']);
        }

        $filas = $this->extraerFilasDelPdf(Storage::path($rutaTemporal));

        $idRolEstudiante = Role::where('nombre', 'Estudiante')->value('id_roles');
        abort_if(is_null($idRolEstudiante), 500, "No existe el rol 'Estudiante' en la base de datos.");

        $creados = 0;
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

                $fechaNacimiento = $this->parseFechaRude($fila['fecha_nacimiento_texto']);

                if (!$fechaNacimiento) {
                    throw new \Exception("No se pudo interpretar la fecha: '{$fila['fecha_nacimiento_texto']}'.");
                }

                [$apellidoP, $apellidoM, $nombres] = $this->separarNombreCompleto($fila['nombre_completo']);
                $genero = strtoupper($fila['genero']);
                $codigoRude = $fila['codigo_rude'];
                $departamento = $fila['departamento'] ?: null;

                DB::transaction(function () use ($carnet, $codigoRude, $idRolEstudiante, $apellidoP, $apellidoM, $nombres, $genero, $fechaNacimiento, $departamento, &$creados) {
                    $persona = Persona::where('ci', $carnet)->first();

                    if ($persona) {
                        return; // ya existe, no se toca
                    }

                    $persona = Persona::create([
                        'nombres' => $nombres,
                        'apellido_p' => $apellidoP,
                        'apellido_m' => $apellidoM,
                        'sexo' => $genero,
                        'ci' => $carnet,
                        'fecha_nacimiento' => $fechaNacimiento,
                        'departamento_residencia' => $departamento,
                    ]);

                    $email = $this->generarEmailInstitucional($nombres, $apellidoP, $apellidoM);

                    Usuario::create([
                        'email' => $email,
                        'user' => Str::before($email, '@'),
                        'password' => Hash::make($carnet),
                        'id_roles' => $idRolEstudiante,
                        'id_personas' => $persona->id_personas,
                        'activo' => true,
                    ]);

                    Estudiante::create([
                        'id_personas' => $persona->id_personas,
                        'rude' => $codigoRude,
                    ]);

                    $creados++;
                });
            } catch (\Throwable $e) {
                $errores[] = "Fila {$numeroFila} (Carnet {$carnet}): " . $e->getMessage();
            }
        }

        Storage::delete($rutaTemporal);
        session()->forget(self::SESSION_KEY);

        return redirect()
            ->route('estudiantes.index')
            ->with('exito', "Importación finalizada: {$creados} estudiantes creados, {$omitidos} omitidos (retirados/trasladados).")
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

    private function parseFechaRude(string $texto): ?string
    {
        if (!preg_match('/(\d{1,2})\s+de\s+([a-zA-Záéíóúñ]+)\.?\s+de\s+(\d{4})/iu', $texto, $m)) {
            return null;
        }

        $dia = str_pad($m[1], 2, '0', STR_PAD_LEFT);
        $mes = $this->mesesEspanol[strtolower(substr($m[2], 0, 3))] ?? null;

        return $mes ? "{$m[3]}-{$mes}-{$dia}" : null;
    }

    private function separarNombreCompleto(string $nombreCompleto): array
    {
        $tokens = preg_split('/\s+/', trim($nombreCompleto));

        $apellidoP = $tokens[0] ?? '';
        $apellidoM = count($tokens) > 2 ? ($tokens[1] ?? null) : null;
        $nombres = count($tokens) > 2
            ? implode(' ', array_slice($tokens, 2))
            : ($tokens[1] ?? '');

        return [$apellidoP, $apellidoM, $nombres];
    }

    private function generarEmailInstitucional(string $nombres, string $apellidoP, ?string $apellidoM): string
    {
        $primerNombre = Str::slug(explode(' ', trim($nombres))[0] ?? '', '');
        $apP = Str::slug($apellidoP, '');
        $apM = $apellidoM ? Str::slug($apellidoM, '') : null;

        $base = implode('.', array_filter([$primerNombre, $apP, $apM]));

        $email = "{$base}@" . self::DOMINIO_INSTITUCIONAL;
        $contador = 1;

        while (Usuario::where('email', $email)->exists()) {
            $email = "{$base}{$contador}@" . self::DOMINIO_INSTITUCIONAL;
            $contador++;
        }

        return $email;
    }
}