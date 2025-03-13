<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class EmailVerificationController extends Controller
{
    /**
     * Maneja la verificación de correo.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill(); // Marca el correo como verificado

        return redirect('/dashboard')->with('success', 'Correo verificado exitosamente.');
    }

    /**
     * Reenviar enlace de verificación.
     */
    public function resend(): RedirectResponse
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('info', 'Tu correo ya está verificado.');
        }

        auth()->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Se ha enviado un nuevo enlace de verificación.');
    }
}
