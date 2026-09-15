<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencias';
    protected $fillable = ['id_dias_asistencia', 'id_estudiantes', 'estado'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiantes', 'id_estudiantes');
    }

    public function diaAsistencia()
    {
        return $this->belongsTo(DiaAsistencia::class, 'id_dias_asistencia', 'id_dias_asistencia');
    }
}