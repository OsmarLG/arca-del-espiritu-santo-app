<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;

    protected $fillable = ['fecha_visita', 'tema_enseñado', 'notas', 'peticiones'];

    public function visitable()
    {
        return $this->morphTo();
    }
}
