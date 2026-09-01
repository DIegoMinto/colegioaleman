<?php

namespace Database\Seeders;

use App\Models\Administrativo;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- ROLES ----------
        $rolAdmin = Role::create(['nombre' => 'Administrador']);
        $rolSecretaria = Role::create(['nombre' => 'Secretaría']);
        $rolProfesor = Role::create(['nombre' => 'Profesor']);
        $rolEstudiante = Role::create(['nombre' => 'Estudiante']);

        // ---------- PERSONAS ----------
        $personaAdmin = Persona::create([
            'nombres' => 'Diego',
            'apellido_p' => 'Minto',
            'apellido_m' => 'Arze',
            'ci' => '10834292',
            'fecha_nacimiento' => '2004-03-29',
            'domicilio' => 'San Martín de Porres #96',
            'celular' => '75780041',
            'departamento_residencia' => 'Chuquisaca',
        ]);

        $personaSecretaria = Persona::create([
            'nombres' => 'Ruth Valentina',
            'apellido_p' => 'Campos',
            'apellido_m' => 'Pérez',
            'ci' => '9001002',
            'fecha_nacimiento' => '1988-03-22',
            'domicilio' => 'Calle Bolivar 456',
            'celular' => '70022233',
            'departamento_residencia' => 'Chuquisaca',
        ]);

        $personaDocente1 = Persona::create([
            'nombres' => 'Jhamil',
            'apellido_p' => 'Zeballos',
            'apellido_m' => 'Soruco',
            'ci' => '9001003',
            'fecha_nacimiento' => '1985-07-10',
            'domicilio' => 'Calle Junin 789',
            'celular' => '70033344',
            'departamento_residencia' => 'Chuquisaca',
        ]);

        $personaDocente2 = Persona::create([
            'nombres' => 'Carlos',
            'apellido_p' => 'Montellano',
            'apellido_m' => 'Barriga',
            'ci' => '9001004',
            'fecha_nacimiento' => '1987-11-05',
            'domicilio' => 'Calle Ravelo 321',
            'celular' => '70044455',
            'departamento_residencia' => 'Chuquisaca',
        ]);

        $personaEstudiante1 = Persona::create([
            'nombres' => 'Carlos',
            'apellido_p' => 'Pacheco',
            'apellido_m' => '-',
            'ci' => '9001005',
            'fecha_nacimiento' => '2011-02-18',
            'domicilio' => 'Calle Dalence 654',
            'celular' => '70055566',
            'departamento_residencia' => 'Chuquisaca',
        ]);

        $personaEstudiante2 = Persona::create([
            'nombres' => 'Erick',
            'apellido_p' => 'Medrano',
            'apellido_m' => 'Daza',
            'ci' => '9001006',
            'fecha_nacimiento' => '2011-05-30',
            'domicilio' => 'Calle Camargo 987',
            'celular' => '70066677',
            'departamento_residencia' => 'Chuquisaca',
        ]);

        $passwordDefault = Hash::make('Muydificil.12345');

        Usuario::create(['email' => 'admin@cba.edu.bo', 'user' => 'admin', 'password' => $passwordDefault, 'id_roles' => $rolAdmin->id_roles, 'id_personas' => $personaAdmin->id_personas]);
        Usuario::create(['email' => 'secretaria@cba.edu.bo', 'user' => 'secretaria', 'password' => $passwordDefault, 'id_roles' => $rolSecretaria->id_roles, 'id_personas' => $personaSecretaria->id_personas]);
        Usuario::create(['email' => 'crojas@cba.edu.bo', 'user' => 'crojas', 'password' => $passwordDefault, 'id_roles' => $rolProfesor->id_roles, 'id_personas' => $personaDocente1->id_personas]);
        Usuario::create(['email' => 'avargas@cba.edu.bo', 'user' => 'avargas', 'password' => $passwordDefault, 'id_roles' => $rolProfesor->id_roles, 'id_personas' => $personaDocente2->id_personas]);
        Usuario::create(['email' => 'sgutierrez@cba.edu.bo', 'user' => 'sgutierrez', 'password' => $passwordDefault, 'id_roles' => $rolEstudiante->id_roles, 'id_personas' => $personaEstudiante1->id_personas]);
        Usuario::create(['email' => 'mflores@cba.edu.bo', 'user' => 'mflores', 'password' => $passwordDefault, 'id_roles' => $rolEstudiante->id_roles, 'id_personas' => $personaEstudiante2->id_personas]);

        Administrativo::create(['id_personas' => $personaAdmin->id_personas]);
        Administrativo::create(['id_personas' => $personaSecretaria->id_personas]);

        Docente::create(['id_personas' => $personaDocente1->id_personas]);
        Docente::create(['id_personas' => $personaDocente2->id_personas]);

        Estudiante::create(['id_personas' => $personaEstudiante1->id_personas]);
        Estudiante::create(['id_personas' => $personaEstudiante2->id_personas]);
    }
}