<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listino extends Model
{
    protected $table = 'listino';

    protected $fillable = [
        'categoria', 'descrizione', 'costo', 'prezzolistino', 'iva'
    ];

    public function prodotto()
    {
        return $this->hasMany(Product::class, 'id_listino', 'id');
    }

    public function provaprodotti()
    {
        return $this->belongsToMany(Prova::class, 'proveprodotti', 'id_listino', 'id_prova')->withTimestamps();
    }
}
