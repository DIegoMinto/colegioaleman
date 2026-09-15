<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaPersonaUsuario;
use App\Models\Administrativo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdministrativoController extends Controller
{
    use GestionaPersonaUsuario;

    public function index()
    {
        $administrativos = Administrativo::with('persona.usuario')->paginate(10);
        return view('administrativos.index', compact('administrativos'));
    }

    public function edit(Administrativo $administrativo)
    {
        $administrativo->load('persona.usuario');
        return view('administrativos.edit', compact('administrativo'));
    }

    public function update(Request $request, Administrativo $administrativo)
    {
        $this->actualizarPersonaUsuario($request, $administrativo->persona, $administrativo->persona->usuario);
        return redirect()->route('administrativos.index')->with('exito', 'Administrativo actualizado correctamente.');
    }

    public function toggleEstado(Administrativo $administrativo)
    {
        $this->alternarEstado($administrativo->persona->usuario);
        return back()->with('exito', 'Estado actualizado.');
    }

    public function exportarPdf()
    {
        $administrativos = Administrativo::with('persona.usuario')->get();

        $pdf = Pdf::loadView('pdf.listado-administrativos', compact('administrativos'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('administrativos_' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportarCsv()
    {
        $administrativos = Administrativo::with('persona.usuario')->get();

        return response()->streamDownload(function () use ($administrativos) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nombres', 'apellido_p', 'apellido_m', 'ci', 'sexo', 'fecha_nacimiento', 'celular', 'domicilio', 'departamento_residencia', 'email', 'user', 'activo']);

            foreach ($administrativos as $a) {
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
        }, 'administrativos_' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}