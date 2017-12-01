<?php

use Faker\Generator as Faker;

$factory->define(App\Testimonial::class, function (Faker $faker) {
    return [
        'title' => $faker->bs,
        'body' => $faker->text,
        'author_id' => factory(App\User::class)->lazy()
    ];
});
