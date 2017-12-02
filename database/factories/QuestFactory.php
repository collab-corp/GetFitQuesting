<?php

use Faker\Generator as Faker;

$factory->define(App\Quest::class, function (Faker $faker) {
    return [
        'difficulty' => $faker->numberBetween(0, 5),
        'experience' => 5,
        'requires_team' => false,
        'name' =>  $faker->bs,
        'type' => $faker->randomElement(['cardio', 'strength', 'power', 'muscle', 'event']),
        'description' => $faker->paragraph
    ];
});
