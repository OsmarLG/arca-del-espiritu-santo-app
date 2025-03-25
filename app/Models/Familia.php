<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Familia extends Model
{
    use SoftDeletes;

    protected $table = 'familias';

    protected $fillable = ['nombre'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'familia_users')
            ->withTimestamps()
            ->withPivot('id') // opcional si quieres acceder al id del registro intermedio
            ->using(FamiliaUser::class); // si estás usando modelo para la tabla intermedia
    }

    public function donaciones()
    {
        return $this->hasMany(Donacion::class, 'donable_id')->where('donable_type', self::class);
    }
}

