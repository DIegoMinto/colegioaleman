<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaPersonaUsuario;
use App\Models\Estudiante;
use App\Models\Curso;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EstudianteController extends Controller
{
    use GestionaPersonaUsuario;

    public function index()
    {
        $estudiantes = Estudiante::with('persona.usuario')
            ->paginate(10);
        $cursos = Curso::orderBy('nombre')->orderBy('paralelo')->get();

        return view('estudiantes.index', compact('estudiantes', 'cursos'));
    }

    public function edit(Estudiante $estudiante)
    {
        $estudiante->load('persona.usuario');
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $this->actualizarPersonaUsuario($request, $estudiante->persona, $estudiante->persona->usuario);
        return redirect()->route('estudiantes.index')->with('exito', 'Estudiante actualizado correctamente.');
    }

    public function toggleEstado(Estudiante $estudiante)
    {
        $this->alternarEstado($estudiante->persona->usuario);
        return back()->with('exito', 'Estado actualizado.');
    }

    public function exportarPdf()
    {
        $estudiantes = Estudiante::with('persona.usuario')->get();

        $pdf = Pdf::loadView('pdf.listado-estudiantes', compact('estudiantes'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('estudiantes_' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportarCsv()
    {
        $estudiantes = Estudiante::with('persona.usuario')->get();

        return response()->streamDownload(function () use ($estudiantes) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['codigo_rude', 'nombres', 'apellido_p', 'apellido_m', 'ci', 'sexo', 'fecha_nacimiento', 'celular', 'domicilio', 'departamento_residencia', 'email', 'user', 'activo']);

            foreach ($estudiantes as $e) {
                $p = $e->persona;
                $u = $p->usuario;
                fputcsv($out, [
                    $e->rude,
                    $p->nombres,
                    $p->apellido_p,
                    $p->apellido_m,
                    $p->ci,
                    $p->sexo,
                    optional($p->fecha_nacimiento)->format('Y-m-d'),
                    $p->celular,
                    $p->domicilio,
                    $p->departamento_residencia,
                    $u->email ?? '',
                    $u->user ?? '',
                    $u->activo ? 1 : 0,
                ]);
            }
            fclose($out);
        }, 'estudiantes_' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}