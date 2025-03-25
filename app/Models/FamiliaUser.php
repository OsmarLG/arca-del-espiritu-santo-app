<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamiliaUser extends Pivot
{
    use SoftDeletes;

    protected $table = 'familia_users';

    protected $fillable = ['familia_id', 'user_id'];

    public function familia()
    {
        return $this->belongsTo(Familia::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

