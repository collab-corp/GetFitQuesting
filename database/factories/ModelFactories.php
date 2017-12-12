<?php

use Faker\Generator as Faker;

$factory->define(\Gstt\Achievements\Model\AchievementDetails::class, function (Faker $faker) {
	static $points = 1;
	static $secret = false;
	static $class_name = 'Tests\Fakes\Achievement\TenPointsFakeAchievement';

    return [
       'name' => $faker->bs,
       'description' => $faker->paragraph,
       'points' =>  $points,
       'secret' => $secret,
       'class_name' => $class_name
    ];
});

$factory->define(\Gstt\Achievements\Model\AchievementProgress::class, function (Faker $faker) {
    static $points = 0;

    return [
       'achievement_id' => factory(\Gstt\Achievements\Model\AchievementDetails::class)->lazy(),
       // 'achiever_type' => '',
       // 'achiever_id' => 0,
       'points' => $points,
       'unlocked_at' => null
    ];
});

$factory->state(\Gstt\Achievements\Model\AchievementProgress::class, 'unlocked', function () {
  return ['unlocked_at' => now()];
});
