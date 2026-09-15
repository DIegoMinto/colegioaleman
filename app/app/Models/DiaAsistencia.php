<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaAsistencia extends Model
{
    protected $table = 'dias_asistencia';
    protected $primaryKey = 'id_dias_asistencia';
    protected $fillable = ['id_asignaciones', 'id_trimestres', 'fecha', 'orden'];

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_dias_asistencia', 'id_dias_asistencia');
    }
}