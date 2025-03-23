<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create(['key' => 'app_name', 'value' => 'Arca del Espíritu Santo']);
        Setting::create(['key' => 'app_description', 'value' => 'Sistema de gestión de la iglesia Arca del Espíritu Santo']);
        Setting::create(['key' => 'app_logo', 'value' => 'storage/logo_arca.jpg']);
    }
}
