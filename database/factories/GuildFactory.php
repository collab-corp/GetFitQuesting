<?php

use Faker\Generator as Faker;

$factory->define(App\Guild::class, function (Faker $faker) {
    return [
    	'creator_id' => factory(App\User::class)->lazy(),
        'name' => $faker->bs,
        'description' => $faker->paragraph
    ];
});
