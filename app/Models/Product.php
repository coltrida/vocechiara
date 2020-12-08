<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'matricola', 'stato', 'id_prova', 'destinazione', 'id_listino', 'quantita'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class,'id_cliente', 'id');  // MODO ESTESO
    }

    public function prova()
    {
        return $this->belongsTo(Prova::class,'id_prova', 'id');  // MODO ESTESO
    }

    public function listi()
    {
        return $this->belongsTo(Listino::class,'id_listino', 'id');  // MODO ESTESO
    }

}
