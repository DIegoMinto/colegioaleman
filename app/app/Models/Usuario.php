<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuarios';
    protected $fillable = ['email', 'user', 'password', 'id_roles', 'id_personas', 'activo'];
    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_roles', 'id_roles');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }
}
