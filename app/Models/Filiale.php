<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiale extends Model
{
    protected $table = 'filiali';

    protected $fillable = [
        'nome', 'indirizzo', 'citta', 'cap', 'provincia'
    ];
}
