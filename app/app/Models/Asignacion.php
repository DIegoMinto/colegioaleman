<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    protected $primaryKey = 'id_asignaciones';
    protected $fillable = ['id_docentes', 'id_cursos', 'id_materias', 'gestion'];

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docentes', 'id_docentes');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_cursos', 'id_cursos');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materias', 'id_materias');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'id_asignaciones', 'id_asignaciones');
    }

    public function autoevaluaciones()
    {
        return $this->hasMany(Autoevaluacion::class, 'id_asignaciones', 'id_asignaciones');
    }
}