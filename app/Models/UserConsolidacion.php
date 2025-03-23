<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConsolidacion extends Model
{
    use HasFactory;

    protected $fillable = ['consolidado_id', 'user_id'];

    public function consolidado()
    {
        return $this->belongsTo(Consolidado::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
