<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Docente;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEstudiantes = Estudiante::count();
        $totalMaestros = Docente::count();

        return view('dashboard', compact('totalEstudiantes', 'totalMaestros'));
    }
}