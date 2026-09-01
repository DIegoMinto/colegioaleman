<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Criterio extends Model
{
    protected $table = 'criterios';
    protected $primaryKey = 'id_criterios';
    protected $fillable = ['id_evaluaciones', 'nombre', 'orden', 'puntaje_maximo'];

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'id_evaluaciones', 'id_evaluaciones');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_criterios', 'id_criterios');
    }
}