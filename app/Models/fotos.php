<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class fotos extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_galeria',
        'imagen',
    ];

    protected $dates = ['deleted_at'];

    public function galeria()
    {
        return $this->belongsTo(Galeria::class, 'id_galeria');
    }
}
