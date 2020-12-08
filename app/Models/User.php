<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'magazzino', 'email', 'ruolo', 'password', 'magazzino'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'user_id', 'id');
    }

    public function isAdmin() {
        return $this->ruolo === 'Admin';
    }

    public function isAudio() {
        return $this->ruolo === 'Audio';
    }

    public function isAmministrativa() {
        return $this->ruolo === 'Amministrativa';
    }

    public function isCarla() {
        return $this->email === 'carla';
    }

}
