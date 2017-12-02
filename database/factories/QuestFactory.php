<?php

use Faker\Generator as Faker;

$factory->define(App\Quest::class, function (Faker $faker) {
    return [
        'creator_id' => factory(App\User::class)->lazy(),
        'difficulty' => $faker->numberBetween(0, 5),
        'experience' => 5,
        'name' =>  $faker->bs,
        'type' => $faker->randomElement(\App\Quest::validTypes()),
        'description' => $faker->paragraph
    ];
});
