<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donacion extends Model
{
    use SoftDeletes;

    protected $table = 'donaciones';

    protected $fillable = [
        'donable_id',
        'donable_type',
        'producto_id',
        'cantidad',
    ];

    public function donable()
    {
        return $this->morphTo();
    }

    public function producto()
    {
        return $this->belongsTo(Product::class);
    }
}
