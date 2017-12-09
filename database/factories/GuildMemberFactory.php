<?php

use Faker\Generator as Faker;

$factory->define(App\GuildMember::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\User::class)->lazy(),
        'guild_id' => factory(App\Guild::class)->lazy()
    ];
});
