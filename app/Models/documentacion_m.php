<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class documentacion_m extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_marianas',
        'archivo',
    ];
    
    protected $dates = ['deleted_at'];

    public function mariana()
    {
        return $this->belongsTo(marianas::class, 'id_marianas');
    }
}
