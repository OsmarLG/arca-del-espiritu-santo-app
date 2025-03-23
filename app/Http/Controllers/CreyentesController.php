<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreyentesController extends Controller
{
    public function index()
    {
        if (!auth()->user()->doesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'master', 'lider', 'consolidador',
            'evangelista', 'maestro', 'profeta', 'padre_espiritual', 'maestro_espiritual']);
        })) {
            abort(403);
        }

        return view('creyentes.index');
    }
}
