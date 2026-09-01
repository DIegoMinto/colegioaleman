<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Autoevaluacion extends Model
{
    protected $table = 'autoevaluaciones';
    protected $primaryKey = 'id_autoevaluaciones';
    protected $fillable = ['id_asignaciones', 'id_estudiantes', 'id_trimestres'];

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignaciones', 'id_asignaciones');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiantes', 'id_estudiantes');
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'id_trimestres', 'id_trimestres');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleAutoevaluacion::class, 'id_autoevaluaciones', 'id_autoevaluaciones');
    }
}