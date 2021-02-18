<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    //use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'passwordkey', 'code', 'edit_mode'
    ];

    protected $hidden = [
        'passwordkey', 'password', 'remember_token',
    ];

    public function passwords()
    {
        return $this->hasMany(Password::class);
    }
}
