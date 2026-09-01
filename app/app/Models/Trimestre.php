<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    protected $table = 'trimestres';
    protected $primaryKey = 'id_trimestres';
    protected $fillable = ['nombres', 'orden', 'gestion'];

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'id_trimestres', 'id_trimestres');
    }
}