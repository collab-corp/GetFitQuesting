<?php

namespace Tests\Feature\Guild;

use App\Guild;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuildTeamsTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function visitorCanListTeamsInAGuild() 
	{
		$guild = create(Guild::class);
		$teams = factory(Team::class)->times(2)->create(['guild_id' => $guild->id]);

		$results = $this->json('GET', route('guild.teams', $guild))
			 ->assertSuccessful()
			 ->json()['data'];

		$this->assertCount(2, $results);
	} 

	/** @test */
	function guildMemberCanCreateAGuildTeam() 
	{
		$guild = create(Guild::class);
		$member = create(\App\GuildMember::class, ['guild_id' => $guild->id]);

		$this->signIn($member->user);

		$this->json('POST', route('teams.store'), ['guild_id' => $guild->id, 'name' => 'Team blue'])
			 ->assertSuccessful();

		$this->assertDatabaseHas('teams', [
			'guild_id' => $guild->id,
			'name' => 'Team blue'
		]);
	} 

	/** @test */
	function userCannotCreateATeamInAGuildTheyAreNotAMemberOf() 
	{
		$guild = create(Guild::class);

		$this->signIn();

		$this->json('POST', route('teams.store'), ['guild_id' => $guild->id, 'name' => 'Team red'])
			 ->assertJsonValidationErrors('guild_id');

		$this->assertDatabaseMissing('teams', [
			'guild_id' => $guild->id,
			'name' => 'Team red'
		]);
	} 
}
