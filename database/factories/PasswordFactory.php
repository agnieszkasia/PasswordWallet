<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Password;
use Faker\Generator as Faker;

$factory->define(Password::class, function (Faker $faker) {
    return [
        'id' => '2',
        'user_id' => '1',
        'web_address' => 'testing.com',
        'login' => 'test',
        'password' => '123',
        'description' => 'opis',
    ];
});
