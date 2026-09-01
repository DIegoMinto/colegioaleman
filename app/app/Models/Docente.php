<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'docentes';
    protected $primaryKey = 'id_docentes';
    protected $fillable = ['id_personas'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }
}
