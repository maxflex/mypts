<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Entry;
use Faker\Generator as Faker;

$factory->define(Entry::class, function (Faker $faker) {
    return [
        'comment' => $faker->realText(20),
        'pts' => round(rand(10, 100) / 10) * 10 * (rand(1, 10) > 8 ? -1 : 1),
    ];
});
