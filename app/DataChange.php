<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataChange extends Model
{
    protected $fillable = [
        'user_id',
        'password_id',
        'function_id',
        'previous_web_address',
        'previous_description',
        'previous_login',
        'previous_password',
    ];
}
