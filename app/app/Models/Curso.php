<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'id_cursos';
    protected $fillable = ['nombre', 'nivel', 'paralelo'];

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_cursos', 'id_cursos');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_cursos', 'id_cursos');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_cursos', 'id_cursos');
    }
}