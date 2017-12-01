<?php

namespace Tests\Unit;

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
    public function aQuestCanHaveTags()
    {
    }

    /** @test */
    public function aQuestHaveMediaFiles()
    {
    }

    /** @test */
    public function aQuestCanRequireATeam()
    {
    }
}
