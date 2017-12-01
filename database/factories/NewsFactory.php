<?php

use Faker\Generator as Faker;

$factory->define(App\News::class, function ($faker) {
    return [
        'author_id' =>  factory(\App\User::class)->lazy(),
        'title'     =>  $faker->title,
        'content'   =>  $faker->paragraph
    ];
});

$factory->state(App\News::class, 'published', function ($faker) {
    return [
        'published' =>  true
    ];
});

$factory->state(App\News::class, 'withImages', function ($faker) {
    return [
        'images'    =>  function () use ($faker) {
            $images = [];
            for ($i=0; $i < 5; $i++) {
                $images[] = $faker->image();
            }

            return $images;
        }
    ];
});
