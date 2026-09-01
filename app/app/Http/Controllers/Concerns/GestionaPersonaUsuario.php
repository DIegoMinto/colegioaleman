<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

trait GestionaPersonaUsuario
{
    protected function reglasPersonaUsuario($idPersona, $idUsuario): array
    {
        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellido_p' => ['required', 'string', 'max:100'],
            'apellido_m' => ['nullable', 'string', 'max:100'],
            'sexo' => 'required|in:M,F',
            'ci' => ['required', 'string', 'max:20', Rule::unique('personas', 'ci')->ignore($idPersona, 'id_personas')],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'domicilio' => ['nullable', 'string', 'max:200'],
            'celular' => ['nullable', 'string', 'max:20'],
            'departamento_residencia' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', Rule::unique('usuarios', 'email')->ignore($idUsuario, 'id_usuarios')],
            'user' => ['required', 'string', 'max:50', Rule::unique('usuarios', 'user')->ignore($idUsuario, 'id_usuarios')],
        ];
    }

    protected function actualizarPersonaUsuario(Request $request, $persona, $usuario): void
    {
        $datos = $request->validate($this->reglasPersonaUsuario($persona->id_personas, $usuario->id_usuarios));

        DB::transaction(function () use ($persona, $usuario, $datos) {
            $persona->update([
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

            $usuario->update([
                'email' => $datos['email'],
                'user' => $datos['user'],
            ]);
        });
    }

    protected function alternarEstado($usuario): void
    {
        $usuario->update(['activo' => !$usuario->activo]);
    }
}