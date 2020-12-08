<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $fillable = [
        'testo', 'id_cliente'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class,'id_cliente', 'id');  // MODO ESTESO
    }
}

