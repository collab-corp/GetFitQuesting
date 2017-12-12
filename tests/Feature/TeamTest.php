<?php

namespace Tests\Feature;

use App\Team;
use Gstt\Achievements\Model\AchievementDetails;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function it_shows_a_single_team() 
	{
		$team = create(Team::class)->create(['name' => 'Team yellow']);
    	$achievement = $team->achievements()->create(['achievement_id' => create(AchievementDetails::class)->id]);

    	$this->json('GET', route('teams.show', $team), ['relations' => ['achievements']])
    		 ->assertSuccessful()
    		 ->assertJsonFragment([
    		 	'id' => $achievement->id,
    		 	'achiever_id' => $team->id,
    		 	'achiever_type' => Team::class
    		 ]);
	} 
}
