<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Integration
 */
class SearchingForStoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canSearchForStories()
    {
        config(['scout.driver' => 'algolia']);

        $story = create(\App\Story::class, ['name' => 'the greatest story in the world.']);

        do {
            // Account for latency.
            sleep(.25);

            $results = $this->json('GET', route('stories.index'), ['search' => 'the greatest story in the world.'])
                        ->assertSuccessful()
                        ->assertSee('the greatest story in the world.')
                        ->json()['data'];
        } while (empty($results));

        $this->assertCount(1, $results);

        $story->unsearchable();
    }
}
