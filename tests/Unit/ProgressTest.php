<?php

namespace Tests\Unit;

use App\Events\Progress\ProgressCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\InMemoryDatabase;
use Tests\TestCase;

class ProgressTest extends TestCase
{
    use RefreshDatabase, InMemoryDatabase;

    /** @test */
    public function completingAQuestAddsToProgress()
    {
        $quest = create(\App\Quest::class, ['difficulty' => 2, 'experience' => 10]);

        $user = create(\App\User::class);

        $quest->complete($user);

        $this->assertDatabaseHas('progress', [
            'user_id' => $user->id,
            'team_id' => null,
            'quest_id' => $quest->id,
            'experience' => 20 // difficulty * experience
        ]);
    }
}
