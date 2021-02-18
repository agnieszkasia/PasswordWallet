<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LockedUser extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip', 'login', 'attempts', 'lock_time'
    ];
}
