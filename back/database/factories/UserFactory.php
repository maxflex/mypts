<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;


$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$DCqwX3z/vnElFLh7cXaP/.V.tGQyTTPFnHvM6lw9j.yXP7.JnLese', // password
    ];
});

$factory->afterCreating(User::class, fn ($user) => $user->record()->create());
