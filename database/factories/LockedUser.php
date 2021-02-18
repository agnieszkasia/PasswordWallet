<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\LockedUser;
use Faker\Generator as Faker;

$factory->define(LockedUser::class, function (Faker $faker) {
    return [
        'id' => '1',
        'ip' => null,
        'login' => 'test',
        'attempts' => '2',
        'lock_time' => '2020-12-01 23:59:59'
    ];
});
