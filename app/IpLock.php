<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpLock extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'login', 'lock'
    ];
}
