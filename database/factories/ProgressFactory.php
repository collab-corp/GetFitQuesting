<?php

use Faker\Generator as Faker;

$factory->define(\App\Progress::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class)->lazy(),
        'quest_id' => factory(\App\Quest::class)->lazy(),
        'experience' => $faker->numberBetween(5, 100)
    ];
});

$factory->state(\App\Progress::class, 'byTeam', function () {
	return ['team_id' => factory(\App\Team::class)->lazy()];
});
