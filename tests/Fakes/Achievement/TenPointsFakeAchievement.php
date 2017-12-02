<?php

namespace Tests\Fakes\Achievement;

use Gstt\Achievements\Achievement;

class TenPointsFakeAchievement extends Achievement
{
    public $name = 'TenPointsFakeAchievement';

    public $description = 'Dummy achievement';

    public $points = 10;
}
