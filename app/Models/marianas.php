<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class marianas extends Model
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

    public function documentacionMs()
    {
        return $this->hasMany(documentacion_m::class, 'id_marianas');
    }
}
