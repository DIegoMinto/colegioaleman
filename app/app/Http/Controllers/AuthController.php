<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;
use App\Models\Docente;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'user' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credenciales)) {
            if (!Auth::user()->activo) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'user' => 'Tu cuenta está inhabilitada. Contacta a administración.'
                ])->onlyInput('user');
            }

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'user' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('user');
    }

    public function dashboard()
    {
        $totalEstudiantes = Estudiante::count();
        $totalMaestros = Docente::count();

        return view('dashboard', compact('totalEstudiantes', 'totalMaestros'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}