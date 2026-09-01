<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripciones';
    protected $fillable = ['id_estudiantes', 'id_cursos', 'gestion'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiantes', 'id_estudiantes');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_cursos', 'id_cursos');
    }
}