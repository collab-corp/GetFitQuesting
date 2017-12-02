<?php

namespace App\Achievements;

use Gstt\Achievements\Achievement;

class Completed5Quests extends Achievement
{
    /*
     * The achievement name
     */
    public $name = "Completed5Quests";

    /*
     * A small description for the achievement
     */
    public $description = '';

    /**
     * A criteria callback required to obtain the achievement.
     *
     * @return boolean
     */
    public function criteria($progress)
    {
        if (parent::criteria($progress)) {
            return $progress->achiever->quests->count() >= 5;
        } 

        return false;
    }

    public function __construct()
    {
        $this->description = trans('achievements.completed.x_quests', ['count' => 5]);

        parent::__construct();
    }
}
