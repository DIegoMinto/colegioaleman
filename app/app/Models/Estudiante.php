<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    protected $primaryKey = 'id_estudiantes';
    protected $fillable = ['id_personas', 'rude'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_estudiantes', 'id_estudiantes');
    }
}