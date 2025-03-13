<?php

namespace App\Livewire\Setting\V1;

use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class App extends Component
{
    use WithFileUploads, Toast;

    public $app_name;
    public $app_description;
    public $app_logo;
    public $newLogo;
    public bool $update_logo_modal = false;
    protected $default_logo = 'storage/jaguar-removebg.png'; // Ruta del logo predeterminado

    public function mount()
    {
        $this->app_name = Setting::get('app_name', config('app.name'));
        $this->app_description = Setting::get('app_description', 'Descripción predeterminada de la app');
        $this->app_logo = Setting::get('app_logo', $this->default_logo);
    }

    public function updateSettings()
    {
        $this->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'required|string|max:500',
        ]);

        Setting::updateOrCreate(['key' => 'app_name'], ['value' => $this->app_name]);
        Setting::updateOrCreate(['key' => 'app_description'], ['value' => $this->app_description]);

        $this->toast(
            type: 'success',
            title: 'Configuración Guardada',
            description: 'Los ajustes de la aplicación han sido actualizados con éxito.',
            icon: 'o-check-circle',
            css: 'alert-success text-white text-sm',
            timeout: 3000
        );
    }

    public function saveLogo()
    {
        $this->validate([
            'newLogo' => 'image|max:1024', // 1MB máximo
        ]);

        // Guardar la imagen en storage
        $path = $this->newLogo->store('public/logos');
        $path = str_replace('public/', 'storage/', $path);

        Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);

        // Resetear el campo
        $this->reset('newLogo');
        $this->update_logo_modal = false;

        $this->toast(
            type: 'success',
            title: 'Logo Actualizado',
            description: 'El logo de la aplicación ha sido actualizado con éxito.',
            icon: 'o-check-circle',
            css: 'alert-success text-white text-sm',
            timeout: 3000
        );

        return redirect()->route('settings.app');
    }

    public function resetToDefaultLogo()
    {
        $currentLogo = Setting::get('app_logo');

        if ($currentLogo !== $this->default_logo && Storage::exists(str_replace('storage/', 'public/', $currentLogo))) {
            Storage::delete(str_replace('storage/', 'public/', $currentLogo));
        }

        Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $this->default_logo]);

        $this->app_logo = $this->default_logo;
        $this->update_logo_modal = false;

        $this->toast(
            type: 'success',
            title: 'Logo Restaurado',
            description: 'El logo ha sido restaurado al predeterminado.',
            icon: 'o-check-circle',
            css: 'alert-success text-white text-sm',
            timeout: 3000
        );
    }

    public function openUpdateLogoModal()
    {
        $this->reset(['newLogo']);
        $this->update_logo_modal = true;
    }

    public function render()
    {
        return view('livewire.setting.v1.app');
    }
}
