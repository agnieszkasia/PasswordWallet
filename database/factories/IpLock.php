<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\IpLock;
use App\Model;
use Faker\Generator as Faker;

$factory->define(IpLock::class, function (Faker $faker) {
    return [
        'id' => '1',
        'login' => 'test',
        'lock' => '1'
    ];
});
