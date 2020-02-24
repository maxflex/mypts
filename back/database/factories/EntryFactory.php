<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Entry;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Entry::class, function (Faker $faker) {
    return [
        'comment' => $faker->realText(20),
        'pts' => round(rand(10, 100) / 10) * 10 * (rand(1, 10) > 8 ? -1 : 1),
        'user_id' => User::inRandomOrder()->value('id'),
        'created_at' => rand(1, 10) > 7 ? Carbon::yesterday() : Carbon::today(),
    ];
});
