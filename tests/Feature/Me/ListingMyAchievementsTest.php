<?php

namespace Tests\Feature\Me;

use App\User;
use Gstt\Achievements\Model\AchievementDetails;
use Gstt\Achievements\Model\AchievementProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingMyAchievementsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_eager_their_achievements() 
    {
    	$user = create(User::class);

    	$achievement = $user->achievements()->create(['achievement_id' => create(AchievementDetails::class)->id]);

    	$this->signIn($user);

    	$this->json('GET', route('me'), ['relations' => ['achievements']])
    		 ->assertSuccessful()
    		 ->assertJsonFragment([
    		 	'id' => $achievement->id,
    		 	'achiever_id' => $user->id,
    		 	'achiever_type' => User::class
    		 ]);
    } 
}
