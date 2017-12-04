<?php

use Faker\Generator as Faker;

$factory->define(App\Story::class, function (Faker $faker) {
    return [
        'creator_id' => factory(App\User::class)->lazy(),
        'name' => $faker->title,
        'body' => $faker->text
    ];
});
