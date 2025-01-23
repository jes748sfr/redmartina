<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class documentacion_a extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_actividades',
        'archivo',
    ];

    protected $dates = ['deleted_at'];

    public function actividad()
    {
        return $this->belongsTo(actividades::class, 'id_actividades');
    }
}
