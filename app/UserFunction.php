<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFunction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'function_name',
        'description'
    ];
}
