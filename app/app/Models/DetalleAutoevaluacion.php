<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetalleAutoevaluacion extends Model
{
    protected $table = 'detalle_autoevaluacion';
    protected $primaryKey = 'id_detalle_autoevaluacion';
    protected $fillable = ['id_autoevaluaciones', 'id_estudiantes', 'ser', 'saber', 'hacer', 'decidir'];

    public function autoevaluacion()
    {
        return $this->belongsTo(Autoevaluacion::class, 'id_autoevaluaciones', 'id_autoevaluaciones');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiantes', 'id_estudiantes');
    }
}