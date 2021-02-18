<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => 'test',
        'password' => '1a5397875cb4c866dcab9a3b423775ef4f15bc11bfe50187a3064f4e633d106b452e03d4d260bf62f15a67df08ae6ab893f0030c18e86c32550b139b582d465b',
        'passwordkey' => 'owrU":pe!EorCNuD',
        'code' => 'hmac',
        'remember_token' => Str::random(10),
    ];
});
