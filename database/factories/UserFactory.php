<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

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
        'name' => $faker->name,
        'type' => User::USER_TYPE,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$T3rp.OY075GVSFlgUPayDunti5/dhJMHAzDfunX.UQ5bebKDgWqty', // password
        'verify_code' => null,
        'verified_at' => now(),
        'website' => $faker->url,
        'mobile' => '+989' . random_int(11111, 99999) . random_int(1111, 9999)
    ];
});
