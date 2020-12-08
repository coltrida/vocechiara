<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'nome', 'cognome', 'cod_fisc', 'indirizzo', 'cap', 'citta', 'provincia', 'telefono', 'tipo', 'fonte', 'user_id', 'recall', 'datarecall'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');  // MODO ESTESO
    }

    public function fonte()
    {
        return $this->belongsTo(Font::class,'fonte', 'id');  // MODO ESTESO
    }

    public function audiometria()
    {
        return $this->hasMany(Audiometria::class, 'id_cliente', 'id');
    }

    public function prove()
    {
        return $this->hasMany(Prova::class, 'id_cliente', 'id');
    }

    public function nota()
    {
        return $this->hasMany(Note::class, 'id_cliente', 'id');
    }

    public function fattura()
    {
        return $this->hasMany(Fattura::class, 'id_cliente', 'id');
    }
}
