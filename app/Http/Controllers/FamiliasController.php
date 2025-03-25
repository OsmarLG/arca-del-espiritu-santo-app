<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamiliasController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermissionTo('view_menu_familias')) {
            return abort(403);
        }
        return view('familias.index');
    }
}
