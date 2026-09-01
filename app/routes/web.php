<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\CalificacionesController;
use App\Http\Controllers\CriterioController;
use App\Http\Controllers\PlanillaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ---------- ADMINISTRADOR + SECRETARÍA ----------
Route::middleware('role:Administrador,Secretaría')->group(function () {
    Route::get('/registro/estudiante', [RegistroController::class, 'createEstudiante'])->name('registro.estudiante.create');
    Route::post('/registro/estudiante', [RegistroController::class, 'storeEstudiante'])->name('registro.estudiante.store');

    Route::get('/inscripciones', [InscripcionController::class, 'index'])->name('inscripciones.index');
    Route::get('/inscripciones/{curso}', [InscripcionController::class, 'show'])->name('inscripciones.show');
    Route::get('/inscripciones/{curso}/nuevo', [InscripcionController::class, 'create'])->name('inscripciones.create');
    Route::post('/inscripciones/{curso}', [InscripcionController::class, 'store'])->name('inscripciones.store');
    Route::delete('/inscripcion/{inscripcion}', [InscripcionController::class, 'destroy'])->name('inscripciones.destroy');

    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
    Route::get('/estudiantes/{estudiante}/editar', [EstudianteController::class, 'edit'])->name('estudiantes.edit');
    Route::put('/estudiantes/{estudiante}', [EstudianteController::class, 'update'])->name('estudiantes.update');
    Route::patch('/estudiantes/{estudiante}/estado', [EstudianteController::class, 'toggleEstado'])->name('estudiantes.toggle');
});

// ---------- SOLO ADMINISTRADOR ----------
Route::middleware('role:Administrador')->group(function () {
    Route::resource('cursos', CursoController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('materias', MateriaController::class);
    Route::resource('asignaciones', AsignacionController::class)->parameters(['asignaciones' => 'asignacion']);
    Route::resource('trimestres', TrimestreController::class);

    Route::get('/registro/docente', [RegistroController::class, 'createDocente'])->name('registro.docente.create');
    Route::post('/registro/docente', [RegistroController::class, 'storeDocente'])->name('registro.docente.store');
    Route::get('/registro/administrativo', [RegistroController::class, 'createAdministrativo'])->name('registro.administrativo.create');
    Route::post('/registro/administrativo', [RegistroController::class, 'storeAdministrativo'])->name('registro.administrativo.store');

    Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes.index');
    Route::get('/docentes/{docente}/editar', [DocenteController::class, 'edit'])->name('docentes.edit');
    Route::put('/docentes/{docente}', [DocenteController::class, 'update'])->name('docentes.update');
    Route::patch('/docentes/{docente}/estado', [DocenteController::class, 'toggleEstado'])->name('docentes.toggle');

    Route::get('/administrativos', [AdministrativoController::class, 'index'])->name('administrativos.index');
    Route::get('/administrativos/{administrativo}/editar', [AdministrativoController::class, 'edit'])->name('administrativos.edit');
    Route::put('/administrativos/{administrativo}', [AdministrativoController::class, 'update'])->name('administrativos.update');
    Route::patch('/administrativos/{administrativo}/estado', [AdministrativoController::class, 'toggleEstado'])->name('administrativos.toggle');
});

// ---------- ADMINISTRADOR + SECRETARÍA + PROFESOR (navegación y vista de planillas) ----------
Route::middleware('role:Administrador,Secretaría,Profesor')->group(function () {
    Route::get('/calificaciones', [CalificacionesController::class, 'index'])->name('calificaciones.index');
    Route::get('/calificaciones/{trimestre}', [CalificacionesController::class, 'cursos'])->name('calificaciones.cursos');

    Route::get('/planilla/{asignacion}/{trimestre}', [PlanillaController::class, 'show'])->name('planilla.show');
    Route::post('/planilla/{asignacion}/{trimestre}', [PlanillaController::class, 'store'])->name('planilla.store');
    Route::get('/planilla/{asignacion}/{trimestre}/pdf', [PlanillaController::class, 'exportarPdf'])->name('planilla.pdf');
    Route::get('/planilla/{asignacion}/{trimestre}/centralizador', [PlanillaController::class, 'centralizador'])->name('planilla.centralizador');
    Route::get('/centralizador/{asignacion}/{trimestre}/pdf', [PlanillaController::class, 'pdfCentralizador'])->name('centralizador.pdf');

    Route::get('/planilla/{asignacion}/{trimestre}/criterios', [CriterioController::class, 'index'])->name('criterios.index');
    Route::post('/evaluaciones/{evaluacion}/criterios', [CriterioController::class, 'store'])->name('criterios.store');
    Route::delete('/criterios/{criterio}', [CriterioController::class, 'destroy'])->name('criterios.destroy');
    Route::patch('/criterios/{criterio}', [CriterioController::class, 'update'])->name('criterios.update');
});

// ---------- SOLO PROFESOR (edición real) ----------
