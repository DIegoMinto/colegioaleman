<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellido_p' => ['required', 'string', 'max:100'],
            'apellido_m' => ['nullable', 'string', 'max:100'],
            'ci' => ['required', 'string', 'max:20', Rule::unique('personas', 'ci')],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'domicilio' => ['nullable', 'string', 'max:200'],
            'celular' => ['nullable', 'string', 'max:20'],
            'departamento_residencia' => ['nullable', 'string', 'max:50'],

            'email' => ['required', 'email', Rule::unique('usuarios', 'email')],
            'user' => ['required', 'string', 'max:50', Rule::unique('usuarios', 'user')],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'ci.unique' => 'Ya existe una persona registrada con este número de CI.',
            'email.unique' => 'Este correo ya está en uso.',
            'user.unique' => 'Este nombre de usuario ya está en uso.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ];
    }
}
