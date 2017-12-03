<?php

use Faker\Generator as Faker;

$factory->define(\App\Team::class, function (Faker $faker) {
    return [
        'owner_id' => factory(\App\User::class)->lazy(),
        'name' => $faker->bs
    ];
});
