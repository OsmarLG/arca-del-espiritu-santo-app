<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => config('app.name')]);
        Setting::updateOrCreate(['key' => 'app_description'], ['value' => 'Sistema de gestión basado en Laravel 11 con Maru UI']);
        Setting::updateOrCreate(['key' => 'app_logo'], ['value' => 'storage/jaguar-removebg.png']);
    }
}
