<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MinisterioMisericordiaController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermissionTo('view_menu_ministerios')) {
            return abort(403);
        }
        return view('ministerios.misericordia.index');
    }

    public function productos()
    {
        if (!Auth::user()->hasPermissionTo('view_menu_ministerios')) {
            return abort(403);
        }
        return view('ministerios.misericordia.productos');
    }

    public function categorias()
    {
        if (!Auth::user()->hasPermissionTo('view_menu_ministerios')) {
            return abort(403);
        }
        return view('ministerios.misericordia.categorias');
    }
}
