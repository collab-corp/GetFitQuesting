<?php

namespace Tests\Feature;

use App\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeavingAStoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function userCanLeaveAStoryTheyHaveEnrolled()
    {
        $this->signIn($user = create(\App\User::class));

        $user->enroll($story = create(Story::class));

        $this->json('DELETE', route('story.leave', $story))
             ->assertSuccessful();

        $this->assertFalse($user->stories->contains($story), "did not leave story.");
    }

    /** @test */
    public function teamOwnerCanLeaveAStoryTheyHaveEnrolledOnBehalfOfTheTeam()
    {
        $user = create(\App\User::class);
        $team = $user->teams()->create(['name' => 'Team yellow', 'owner_id' => $user->id]);

        $team->enroll($story = create(Story::class));

        $this->signIn($user)
             ->json('DELETE', route('story.leave', $story), ['team_id' => $team->id])
             ->assertSuccessful();

        $this->assertFalse($team->stories->contains($story), "did not leave team story.");
    }

    /** @test */
    public function teamMemberCannotLeaveATeamStory()
    {
    	$this->signIn($user = create(\App\User::class));

    	$story = create(Story::class);

    	$team = create(\App\Team::class);
    	$team->enroll($story);
    	$team->users()->save($user);

    	$this->signIn($user)
             ->json('DELETE', route('story.leave', $story), ['team_id' => $team->id])
             ->assertJsonValidationErrors('team_id');

        $this->assertTrue($team->stories->contains($story));
    }

    /** @test */
    public function cannotLeaveAStoryForAnotherTeam()
    {
        $story = create(Story::class);

        $team = create(\App\Team::class);
        $team->enroll($story);

        $this->signIn(create(\App\User::class))
             ->json('DELETE', route('story.leave', $story), ['team_id' => $team->id])
             ->assertJsonValidationErrors('team_id');
    }
}
