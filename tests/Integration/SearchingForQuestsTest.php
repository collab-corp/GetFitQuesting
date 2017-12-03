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
}