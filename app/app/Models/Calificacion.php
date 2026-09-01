<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificaciones';
    protected $fillable = ['id_criterios', 'id_estudiantes', 'nota'];

    public function criterio()
    {
        return $this->belongsTo(Criterio::class, 'id_criterios', 'id_criterios');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiantes', 'id_estudiantes');
    }
}