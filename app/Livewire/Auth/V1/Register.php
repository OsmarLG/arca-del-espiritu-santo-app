<?php

namespace App\Livewire\Auth\V1;

use App\Models\User;
use App\Notifications\NewUserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Mary\Traits\Toast;

class Register extends Component
{
    use Toast;

    public ?string $name;
    public ?string $username;
    public ?string $email;
    public ?string $password;

    public function register()
    {
        $this->validate([
            'name' => 'required',
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $user = User::create(['name' => $this->name, 'username' => $this->username, 'email' => $this->email, 'password' => Hash::make($this->password)]);

        // Permisos predeterminados
        $defaultPermissions = [
            'view_menu_profile',
            'view_menu_dashboard',
            'view_any_notifications',
            'view_notifications',
            'mark_as_read_notifications',
            'mark_as_unread_notifications',
        ];

        // Asignar los permisos si no los tiene
        foreach ($defaultPermissions as $permission) {
            if (!$user->hasPermissionTo($permission)) {
                $user->givePermissionTo($permission);
            }
        }

        $this->success('Registro exitoso. Revisa tu correo para verificar.');

        event(new Registered($user));

        Auth::login($user);
        $user->notify(new NewUserRegistered($user));

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.v1.register');
    }
}
