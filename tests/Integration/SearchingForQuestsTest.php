<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Integration
 */
class SearchingForQuestsTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
    public function canSearchForQuests()
    {
        config(['scout.driver' => 'algolia']);

        $quest = create(\App\Quest::class, ['type' => 'strength']);

        do {
            // Account for latency.
            sleep(.25);

            $results = $this->json('GET', route('quests.index'), ['search' => 'strength'])
                        ->assertSuccessful()
                        ->assertSee('strength')
                        ->json()['data'];
        } while (empty($results));

        $this->assertCount(1, $results);

        $quest->unsearchable();
    }

    /** @test */
    public function canFilterQuestsByDifficulty()
    {
        config(['scout.driver' => 'algolia']);

        $quests = collect([
                    create(\App\Quest::class, ['difficulty' => 1, 'type' => 'cardio']),
                    create(\App\Quest::class, ['difficulty' => 3, 'type' => 'cardio'])
                ]);

        $quests->searchable();

        do {
            // Account for latency.
            sleep(.25);

            $results = $this->json('GET', route('quests.index'), ['search' => 'cardio', 'difficulty' => 3])
                        ->assertSuccessful()
                        ->json()['data'];
        } while (empty($results));

        $this->assertCount(1, $results);

        $quests->unsearchable();
    }
}