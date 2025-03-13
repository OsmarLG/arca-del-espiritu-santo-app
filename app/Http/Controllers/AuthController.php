<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Vista de login
    public function login()
    {
        return view('auth.login');
    }

    // Vista de registro
    public function register()
    {
        return view('auth.register');
    }

    // Vista de verificación de correo
    public function emailVerify()
    {
        return view('auth.email-verify');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
