<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    protected $table = 'fonts';

    protected $fillable = [
        'name', 'codfisc', 'citta', 'pec', 'univoco'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'fonte', 'id');
    }
}
