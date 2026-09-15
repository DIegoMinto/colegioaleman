<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';
    protected $primaryKey = 'id_evaluaciones';
    protected $fillable = ['nombre', 'tipo', 'porcentaje', 'id_asignaciones', 'id_trimestres'];

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignaciones', 'id_asignaciones');
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'id_trimestres', 'id_trimestres');
    }

    public function criterios()
    {
        return $this->hasMany(Criterio::class, 'id_evaluaciones', 'id_evaluaciones')->orderBy('orden');
    }


}