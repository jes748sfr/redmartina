<?php

namespace App\Models;

use App\Http\Controllers\DocumentacionCController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class convocatoria extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_usu',
        'titulo',
        'cuerpo',
    ];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usu');
    }

    public function documentacionCs()
    {
        return $this->hasMany(documentacion_c::class, 'id_convocatoria');
    }

}
