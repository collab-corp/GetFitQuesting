<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\InMemoryDatabase;
use Tests\TestCase;

class ProgressTest extends TestCase
{
    use RefreshDatabase, InMemoryDatabase;

    /** @test */
    public function completingAQuestAddsToProgress()
    {
        $quest = create(\App\Quest::class);

        $user = create(\App\User::class);

        $quest->complete($user);

        $this->assertDatabaseHas('progress', [
            'user_id' => $user->id,
            'quest_id' => $quest->id
        ]);
    }

    /** @test */
    public function progressIsMeasuredBySummerizingQuestExperience()
    {
    }

    /** @test */
    public function achievementsGetsUnlockedByProgress()
    {
    }

    /** @test */
    public function canFilterQuestsByDificulty()
    {
    }
}
