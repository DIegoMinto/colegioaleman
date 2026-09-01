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

class RegistroController extends Controller
{

    public function createEstudiante()
    {
        return view('registro.estudiante');
    }

    public function storeEstudiante(StoreEstudianteRequest $request)
    {
        DB::transaction(function () use ($request) {
            $persona = $this->crearPersona($request->validated());
            $usuario = $this->crearUsuario($request->validated(), $persona, 'Estudiante');

            Estudiante::create(['id_personas' => $persona->id_personas]);
        });

        return redirect()->route('dashboard')->with('exito', 'Estudiante registrado correctamente.');
    }


    public function createDocente()
    {
        return view('registro.docente');
    }

    public function storeDocente(StoreDocenteRequest $request)
    {
        DB::transaction(function () use ($request) {
            $persona = $this->crearPersona($request->validated());
            $usuario = $this->crearUsuario($request->validated(), $persona, 'Profesor');

            Docente::create(['id_personas' => $persona->id_personas]);
        });

        return redirect()->route('dashboard')->with('exito', 'Profesor registrado correctamente.');
    }

    public function createAdministrativo()
    {
        return view('registro.administrativo');
    }

    public function storeAdministrativo(StoreAdministrativoRequest $request)
    {
        DB::transaction(function () use ($request) {
            $persona = $this->crearPersona($request->validated());
            $usuario = $this->crearUsuario($request->validated(), $persona, 'Administrador');

            Administrativo::create(['id_personas' => $persona->id_personas]);
        });

        return redirect()->route('dashboard')->with('exito', 'Administrativo registrado correctamente.');
    }

    private function crearPersona(array $datos): Persona
    {
        return Persona::create([
            'nombres' => $datos['nombres'],
            'apellido_p' => $datos['apellido_p'],
            'apellido_m' => $datos['apellido_m'] ?? null,
            'sexo' => $datos['sexo'],
            'ci' => $datos['ci'],
            'fecha_nacimiento' => $datos['fecha_nacimiento'],
            'domicilio' => $datos['domicilio'] ?? null,
            'celular' => $datos['celular'] ?? null,
            'departamento_residencia' => $datos['departamento_residencia'] ?? null,
        ]);
    }

    private function crearUsuario(array $datos, Persona $persona, string $nombreRol): Usuario
    {
        $rol = Role::where('nombre', $nombreRol)->firstOrFail();

        return Usuario::create([
            'email' => $datos['email'],
            'user' => $datos['user'],
            'password' => Hash::make($datos['password']),
            'id_roles' => $rol->id_roles,
            'id_personas' => $persona->id_personas,
        ]);
    }
}