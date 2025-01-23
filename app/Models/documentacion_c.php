<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class documentacion_c extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_convocatoria',
        'archivo',
    ];

    protected $dates = ['deleted_at'];

    public function convocatoria()
    {
        return $this->belongsTo(Convocatoria::class, 'id_convocatoria');
    }
}
