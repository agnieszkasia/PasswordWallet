<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FailedLoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'name', 'ip_address', 'status', 'time', 'attempts'
    ];
}
