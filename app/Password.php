<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    protected $fillable = [
        'password',
        'web_address',
        'description',
        'login',
        'deleted',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
