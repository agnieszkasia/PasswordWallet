<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFunctionAction extends Model
{
    protected $fillable = [
        'user_id',
        'function_id'
    ];
}
