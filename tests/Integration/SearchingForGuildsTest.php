<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Guild;

class SearchingForGuildsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canSearchForGuilds()
    {
        config(['scout.driver' => 'algolia']);

        $guild = create(Guild::class, ['name' => 'Blood & Thunder']);

        do {
            // Account for latency.
            sleep(.25);

            $results = $this->json('GET', route('guilds.index'), ['search' => 'Blood & Thunder'])
                        ->assertSuccessful()
                        ->assertSee('Blood & Thunder')
                        ->json()['data'];
        } while (empty($results));

        $this->assertCount(1, $results);

        $guild->unsearchable();
    }
}
