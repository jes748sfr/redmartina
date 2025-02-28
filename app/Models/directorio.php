<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class directorio extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_usu',
        'area',
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
}
