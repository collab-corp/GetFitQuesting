<?php

namespace Tests\Feature;

use App\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollingAStoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function userCanEnrollAStory()
    {
        $story = create(Story::class);

        $this->signIn($user = create(\App\User::class));

        $this->json('POST', route('story.enroll', $story))
             ->assertSuccessful();

        $user->stories->contains($story);

        // $this->assertDatabaseHas('progress', [
        // 	'story_id' => $story->id,
        // 	'user_id' => $user->id
        // ]);
    }

    /** @test */
    public function canEnrollAStoryAsATeam()
    {
        $story = create(Story::class);

        $this->signIn($user = create(\App\User::class));
        $team = $user->teams()->create(['name' => 'Team blue', 'owner_id' => $user->id]);

        $this->json('POST', route('story.enroll', $story), ['team_id' => $team->id])
             ->assertSuccessful();

        $team->stories->contains($story);
    }

    /** @test */
    public function userCannotEnrollAStoryForATeamTheyDoNotOwn()
    {
        $story = create(Story::class);

        $this->signIn($user = create(\App\User::class));

        $this->json('POST', route('story.enroll', $story), ['team_id' => create(\App\Team::class)->id])
             ->assertJsonValidationErrors('team_id');
    }
}
