<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function app()
    {
        if (!Auth::user()->hasPermissionTo('view_menu_settings')) {
            abort(403);
        }

        return view('settings.app');
    }
}
