<?php

namespace App\Livewire\Auth\V1;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function resendVerificationEmail()
    {
        if (Auth::user()) {
            Auth::user()->sendEmailVerificationNotification();
            session()->flash('message', 'Se ha enviado un nuevo correo de verificación.');
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/')->with('message', 'Sesión cerrada correctamente.');
    }


    public function render()
    {
        return view('livewire.auth.v1.verify-email');
    }
}
