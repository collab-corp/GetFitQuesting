<?php

namespace Tests\Unit;

use App\Jobs\User\ScrapOwnedRelations;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class UserRelationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function deletingCascadeDownOnOwnedRelations() 
    {
    	Bus::fake();

    	$user = create(User::class);
    	$team = $user->teams()->create(['owner_id' => $user->id, 'name' => 'Team red']);
    	$token = $user->tokens()->create(['id' => 1, 'client_id' => 1, 'revoked' => false]);

    	$user->delete();

    	Bus::assertDispatched(ScrapOwnedRelations::class, function ($job) use($user) {
    		$job->handle();

    		return $job->user->is($user);
    	});

    	$this->assertDatabaseHas($token->getTable(), [
    		'id' => $token->id,
    		'revoked' => true
    	]);

    	$this->assertDatabaseHas($team->getTable(), [
    		'id' => $team->id,
    		'deleted_at' => now()
    	]);
    } 
}
