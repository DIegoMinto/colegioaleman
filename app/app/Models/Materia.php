<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'materias';
    protected $primaryKey = 'id_materias';
    protected $fillable = ['nombre', 'id_areas'];

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_areas', 'id_areas');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_materias', 'id_materias');
    }
}