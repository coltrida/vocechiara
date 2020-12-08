<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audiometria extends Model
{
    protected $table = 'audiometrie';

    protected $fillable = [
        '_250d', '_250s', '_500d', '_500s', '_1000d', '_1000s', '_2000d', '_2000s', '_4000d', '_4000s', '_8000d', '_8000s', 'id_cliente'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'id_cliente', 'id');  // MODO ESTESO
    }
}
