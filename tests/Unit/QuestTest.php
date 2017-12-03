<?php

namespace Tests\Unit;

use App\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\InMemoryDatabase;
use Tests\TestCase;

class QuestTest extends TestCase
{
    use RefreshDatabase, InMemoryDatabase;

    /** @test */
    public function slugIsGeneratedFromName()
    {
        $quest = create(\App\Quest::class, ['name' => 'something fancy']);

        $this->assertEquals('something-fancy', $quest->slug);
    }

    /** @test */
    public function aQuestHasDefaultAchivements()
    {
        $quest = create(\App\Quest::class);

        $generalAchievements = Achievement::general('quest');
        $quest->achievements->each(function ($achievement) use($generalAchievements) {
            $this->assertContains($achievement->class_name, $generalAchievements);
        });
    }
}
