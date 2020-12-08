<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fattura extends Model
{
    protected $table = 'fatture';

    protected $fillable = [
        'id_prova', 'acconto', 'data_fattura', 'nr_rate'
    ];

    public function prova()
    {
        return $this->belongsTo(Prova::class,'id_prova', 'id');  // MODO ESTESO
    }

}
