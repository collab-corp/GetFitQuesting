<?php

namespace App;

use Gstt\Achievements\Achiever;
use Mpociot\Teamwork\TeamworkTeam;
use Watson\Rememberable\Rememberable;

class Team extends TeamworkTeam
{
	use Achiever, Rememberable, HasProgress;
}
