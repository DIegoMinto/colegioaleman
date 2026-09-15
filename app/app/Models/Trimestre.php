<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Trimestre extends Model
{
    protected $table = 'trimestres';
    protected $primaryKey = 'id_trimestres';
    protected $fillable = ['nombres', 'orden', 'gestion', 'fecha_inicio', 'fecha_fin', 'activo',];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'id_trimestres', 'id_trimestres');
    }

    public function estaActivo(): bool
    {
        return (bool) $this->activo;
    }
}