<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencias';
    protected $fillable = ['id_estudiantes', 'id_cursos', 'fecha', 'estado'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiantes', 'id_estudiantes');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_cursos', 'id_cursos');
    }
}