<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class galeria extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_usu',
        'titulo',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usu');
    }

    public function fotos()
    {
        return $this->hasMany(fotos::class, 'id_galeria');
    }
}
