<?php

namespace App\Models;

use App\Http\Controllers\DocumentacionCController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class convocatoria extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    use Searchable;

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
        return $this->hasMany(documentacion_convocatorias::class, 'id_convocatoria');
    }

    public function toSearchableArray()
    {
        return [
            'titulo' => $this->titulo,
            'cuerpo' => $this->cuerpo,
        ];
    }

}
