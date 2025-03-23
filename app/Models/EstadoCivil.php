<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCivil extends Model
{
    use HasFactory;

    protected $table = 'estados_civiles';

    protected $fillable = ['nombre'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
