<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consolidado extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'dia_visita', 'consolidador_id'];

    public function consolidador()
    {
        return $this->belongsTo(User::class, 'consolidador_id');
    }

    public function usersConsolidacion()
    {
        return $this->hasMany(UserConsolidacion::class, 'consolidado_id');
    }

    public function visitas()
    {
        return $this->morphMany(Visita::class, 'visitable');
    }
}
