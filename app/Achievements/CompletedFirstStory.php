<?php

namespace App\Achievements;

use Gstt\Achievements\Achievement;

class CompletedFirstStory extends Achievement
{
    /*
     * The achievement name
     */
    public $name = "CompletedFirstStory";

    /*
     * A small description for the achievement
     */
    public $description = '';


    public function __construct()
    {
        $this->description = trans('achievements.completed.first_story');

        parent::__construct();
    }
}
