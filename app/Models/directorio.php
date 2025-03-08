<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class directorio extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    protected $fillable = [
        'id_usu',
        'area',
        'nivel',
        'imagen',
        'nombre',
        'correo',
        'descripcion',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usu');
    }

    public function toSearchableArray()
    {
        return [
            'area' => $this->area,
            'nombre' => $this->nombre,
            'correo' => $this->correo,
        ];
    }
}
