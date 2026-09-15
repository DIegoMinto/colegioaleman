<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdministrativoRequest;
use App\Http\Requests\StoreDocenteRequest;
use App\Http\Requests\StoreEstudianteRequest;
use App\Models\Administrativo;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistroController extends Controller
{
    private const DOMINIO_INSTITUCIONAL = 'colegioalemansucre.edu.bo';

    public function createEstudiante()
    {
        return view('registro.estudiante');
    }

    public function storeEstudiante(StoreEstudianteRequest $request)
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos) {
            $persona = $this->crearPersona($datos);
            $idRol = $this->idRolPorNombre('Estudiante');
            $this->crearUsuario($persona, $idRol);

            Estudiante::create(['id_personas' => $persona->id_personas]);
        });

        return redirect()->route('estudiantes.index')->with('exito', 'Estudiante registrado correctamente.');
    }

    public function createDocente()
    {
        return view('registro.docente');
    }

    public function storeDocente(StoreDocenteRequest $request)
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos) {
            $persona = $this->crearPersona($datos);
            $idRol = $this->idRolPorNombre('Profesor');
            $this->crearUsuario($persona, $idRol);

            Docente::create(['id_personas' => $persona->id_personas]);
        });

        return redirect()->route('docentes.index')->with('exito', 'Docente registrado correctamente.');
    }

    public function createAdministrativo()
    {
        return view('registro.administrativo');
    }

    public function storeAdministrativo(StoreAdministrativoRequest $request)
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos) {
            $persona = $this->crearPersona($datos);
            $idRol = $this->idRolPorNombre('Administrador');
            $this->crearUsuario($persona, $idRol);

            Administrativo::create(['id_personas' => $persona->id_personas]);
        });

        return redirect()->route('administrativos.index')->with('exito', 'Administrativo registrado correctamente.');
    }

    private function crearPersona(array $datos): Persona
    {
        return Persona::create([
            'nombres' => $datos['nombres'],
            'apellido_p' => $datos['apellido_p'],
            'apellido_m' => $datos['apellido_m'] ?? null,
            'sexo' => $datos['sexo'] ?? null,
            'ci' => $datos['ci'],
            'fecha_nacimiento' => $datos['fecha_nacimiento'],
            'domicilio' => $datos['domicilio'] ?? null,
            'celular' => $datos['celular'] ?? null,
            'departamento_residencia' => $datos['departamento_residencia'] ?? null,
        ]);
    }

    private function crearUsuario(Persona $persona, int $idRol): Usuario
    {
        $email = $this->generarEmailInstitucional($persona->nombres, $persona->apellido_p, $persona->apellido_m);

        return Usuario::create([
            'email' => $email,
            'user' => Str::before($email, '@'),
            'password' => Hash::make($persona->ci),
            'id_roles' => $idRol,
            'id_personas' => $persona->id_personas,
            'activo' => true,
        ]);
    }

    /**
     * "Diego Minto Perez" -> "diego.minto.perez@dominio"
     * Garantiza unicidad agregando un número incremental si ya existe.
     */
    private function generarEmailInstitucional(string $nombres, string $apellidoP, ?string $apellidoM): string
    {
        $primerNombre = Str::slug(Str::ascii(explode(' ', trim($nombres))[0] ?? ''), '');
        $apP = Str::slug(Str::ascii($apellidoP), '');
        $apM = $apellidoM ? Str::slug(Str::ascii($apellidoM), '') : null;

        $base = implode('.', array_filter([$primerNombre, $apP, $apM]));

        $email = "{$base}@" . self::DOMINIO_INSTITUCIONAL;
        $contador = 1;

        while (Usuario::where('email', $email)->exists()) {
            $email = "{$base}{$contador}@" . self::DOMINIO_INSTITUCIONAL;
            $contador++;
        }

        return $email;
    }

    private function idRolPorNombre(string $nombre): int
    {
        $idRol = Role::where('nombre', $nombre)->value('id_roles');

        abort_if(is_null($idRol), 500, "No existe el rol '{$nombre}' en la base de datos.");

        return $idRol;
    }
}