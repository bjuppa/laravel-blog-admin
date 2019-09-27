<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Bjuppa\LaravelBlogAdmin\Tests\Feature\Fakes\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
    ];
});
