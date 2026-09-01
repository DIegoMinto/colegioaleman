<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    protected $primaryKey = 'id_areas';
    protected $fillable = ['nombre'];

    public function materias()
    {
        return $this->hasMany(Materia::class, 'id_areas', 'id_areas');
    }
}
