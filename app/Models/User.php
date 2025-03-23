<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable  implements MustVerifyEmail

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar',
        'password',
        'status',
        'numero_telefono',
        'direccion',
        'invitador_id',
        'genero_id',
        'estado_civil_id',
        'profesion',
        'fecha_nacimiento',
        'fecha_conversion',
        'viene_otra_iglesia',
        'bautizado'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'viene_otra_iglesia' => 'boolean',
            'bautizado' => 'boolean',
            'fecha_nacimiento' => 'date',
            'fecha_conversion' => 'date',
        ];
    }

    public function invitador()
    {
        return $this->belongsTo(User::class, 'invitador_id');
    }

    public function getEdadAttribute() {
        return $this->fecha_nacimiento->age ?? null;
    }

    public function genero() {
        return $this->belongsTo(Genero::class, 'genero_id');
    }

    public function estado_civil() {
        return $this->belongsTo(EstadoCivil::class, 'estado_civil_id');
    }
}
