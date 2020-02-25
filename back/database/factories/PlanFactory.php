<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'comment' => $faker->realText(20),
        'pts' => round(rand(10, 100) / 10) * 10,
        'user_id' => User::inRandomOrder()->value('id'),
        'is_finished' => rand(1, 10) > 8,
        'date' => rand(1, 10) > 7 ? Carbon::tomorrow()->format('Y-m-d') : Carbon::today()->format('Y-m-d'),
    ];
});
