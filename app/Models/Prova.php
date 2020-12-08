<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
    protected $table = 'prove';

    protected $fillable = [
        'id_cliente', 'id_audio', 'nr_ordine', 'tot', 'stato'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class,'id_cliente', 'id');  // MODO ESTESO
    }

    public function audio()
    {
        return $this->belongsTo(User::class,'id_audio', 'id');  // MODO ESTESO
    }

    public function provaprodotti()
    {
        return $this->belongsToMany(Listino::class, 'proveprodotti', 'id_prova', 'id_listino')->withTimestamps();
    }

    public function prodottiprova()
    {
        return $this->hasMany(ProvaProdotti::class, 'id_prova', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_prova', 'id');
    }

}
