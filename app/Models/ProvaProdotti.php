<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProvaProdotti extends Model
{
    protected $table = 'proveprodotti';

    protected $fillable = [
        'id_prova', 'id_listino', 'prezzo', 'quantita'
    ];

    public function listinoprovaprodotti()
    {
        return $this->hasMany(Listino::class, 'id_listino', 'id');
    }
}
