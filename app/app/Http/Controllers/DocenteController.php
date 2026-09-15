<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaPersonaUsuario;
use App\Models\Docente;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocenteController extends Controller
{
    use GestionaPersonaUsuario;

    public function index()
    {
        $docentes = Docente::with('persona.usuario')->paginate(10);
        return view('docentes.index', compact('docentes'));
    }

    public function edit(Docente $docente)
    {
        $docente->load('persona.usuario');
        return view('docentes.edit', compact('docente'));
    }

    public function update(Request $request, Docente $docente)
    {
        $this->actualizarPersonaUsuario($request, $docente->persona, $docente->persona->usuario);
        return redirect()->route('docentes.index')->with('exito', 'Docente actualizado correctamente.');
    }

    public function toggleEstado(Docente $docente)
    {
        $this->alternarEstado($docente->persona->usuario);
        return back()->with('exito', 'Estado actualizado.');
    }

    public function exportarPdf()
    {
        $docentes = Docente::with('persona.usuario')->get();

        $pdf = Pdf::loadView('pdf.listado-docentes', compact('docentes'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('docentes_' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportarCsv()
    {
        $docentes = Docente::with('persona.usuario')->get();

        return response()->streamDownload(function () use ($docentes) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nombres', 'apellido_p', 'apellido_m', 'ci', 'sexo', 'fecha_nacimiento', 'celular', 'domicilio', 'departamento_residencia', 'email', 'user', 'activo']);

            foreach ($docentes as $a) {
                $p = $a->persona;
                $u = $p->usuario;
                fputcsv($out, [
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
        }, 'docentes_' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}