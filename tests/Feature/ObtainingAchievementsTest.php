<?php

use Gstt\Achievements\Event\Unlocked;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\Fakes\Achievement\TenPointsFakeAchievement;
use Tests\InMemoryDatabase;
use Tests\TestCase;

class ObtainingAchievementsTest extends TestCase
{
    use RefreshDatabase, InMemoryDatabase;

    /** @test */
    public function ReachingRequiredPointsTriggersAchievement()
    {
        Event::fake(Unlocked::class);

        $achievement = new TenPointsFakeAchievement;

        $user = create(App\User::class);
        $quest = create(App\Quest::class, ['difficulty' => 1, 'experience' => 10]);
        $quest->achievements()->save($achievement->getModel());
        
        $quest->complete($user);

        Event::assertDispatched(Unlocked::class, function ($event) use ($user) {
            return $event->progress->achiever->is($user);
        });
    }
}
