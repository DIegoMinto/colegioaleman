<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $primaryKey = 'id_personas';
    protected $fillable = [
        'nombres',
        'apellido_p',
        'apellido_m',
        'sexo',
        'ci',
        'extension_ci',
        'fecha_nacimiento',
        'domicilio',
        'celular',
        'departamento_residencia',
    ];
    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id_personas', 'id_personas');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'id_personas', 'id_personas');
    }

    public function docente()
    {
        return $this->hasOne(Docente::class, 'id_personas', 'id_personas');
    }

    public function administrativo()
    {
        return $this->hasOne(Administrativo::class, 'id_personas', 'id_personas');
    }
}
