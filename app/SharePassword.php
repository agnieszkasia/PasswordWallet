<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharePassword extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email',
        'password_id',
        'password',
        'passwordKey'
    ];
}
