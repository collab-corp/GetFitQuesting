<?php

namespace Tests\Feature;

use App\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creatorCanUpdateQuest()
    {
        $quest = create(\App\Quest::class);

        $this->signIn($quest->creator)
            ->json('PUT', route('quests.update', $quest), ['name' => 'Something'])
            ->assertSuccessful();

        $this->assertDatabaseHas('quests', [
            'id' => $quest->id,
            'creator_id' => $quest->creator_id,
            'name' => 'Something'
        ]);
    }

    /** @test */
    public function creatorCanDeleteQuest()
    {
        $quest = create(\App\Quest::class);

        $this->signIn($quest->creator)
            ->json('DELETE', route('quests.destroy', $quest))
            ->assertSuccessful();

        $this->assertDatabaseMissing('quests', ['id' => $quest->id]);
    }

    /** @test */
    public function adminsCanUpdateAndDeleteAnyQuest()
    {
        $quest = create(\App\Quest::class);

        $admin = create(\App\User::class, ['email' => array_first(Admin::emails())]);

        $this->signIn($admin)
            ->json('PUT', route('quests.update', $quest), ['name' => 'Something'])
            ->assertSuccessful();

        $this->signIn($admin)
            ->json('DELETE', route('quests.destroy', $quest))
            ->assertSuccessful();
    }

    /** @test */
    public function canFilterQuestsByType()
    {
        factory(\App\Quest::class)->times(3)->create(['type' => 'cardio']);
        create(\App\Quest::class, ['type' => 'strength']);

        $results = $this->json('GET', route('quests.index'), ['type' => 'cardio'])
                        ->assertSuccessful()
                        ->assertSee('cardio')
                        ->assertDontSee('strength');

        $this->assertCount(3, $results->decodeResponseJson()['data']);
    }

    /** @test */
    public function canFilterQuestsByDifficulty()
    {
        create(\App\Quest::class, ['difficulty' => 3]);
        //create(\App\Quest::class, ['difficulty' => 1], 2);

        $this->json('GET', route('quests.index'), ['difficulty' => 3])
             ->assertSuccessful()
             ->assertCount(1);
    }

    /** @test */
    public function cannotFilterByAnInvalidType()
    {
        $this->json('GET', route('quests.index'), ['type' => 'invalid-type'])
            ->assertJsonValidationErrors('type');
    }
}
