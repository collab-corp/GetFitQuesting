<?php

namespace Tests\Feature;

use App\Admin;
use App\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guestCanViewAStory()
    {
        $story = create(Story::class, ['name' => 'some story']);

        $this->json('GET', route('stories.show', $story))
             ->assertSuccessful()
             ->assertSee('some story');
    }

    /** @test */
    public function guestCannotCreateAStory()
    {
        $this->json('POST', route('stories.store'), [
            'name' => 'Fancy quest line',
            'body' => 'Thy shall obey the program. Or lose them gains.',
        ])->assertStatus(401);
    }

    /** @test */
    public function userCannotCreateAStoryForAnotherUser()
    {
        $user = create(\App\User::class);

        $this->signIn(create(\App\User::class))
             ->json('POST', route('stories.store'), [
                'name' => 'Fancy quest line',
                'body' => 'Thy shall obey the program. Or lose them gains.',
                'creator_id' => $user->id
             ])->assertJsonValidationErrors('creator_id');
    }

    /** @test */
    public function adminCanCreateAStoryForAnotherUser()
    {
        $user = create(\App\User::class);

        $this->asFirstAdmin()
             ->json('POST', route('stories.store'), [
                'name' => 'Fancy quest line',
                'body' => 'Thy shall obey the program. Or lose them gains.',
                'creator_id' => $user->id
             ])->assertSuccessful();

        $this->assertDatabaseHas('stories', [
            'name' => 'Fancy quest line',
            'body' => 'Thy shall obey the program. Or lose them gains.',
            'creator_id' => $user->id
        ]);
    }

    /** @test */
    public function userCannotUpdateAStoryByAnotherUser()
    {
        $story = create(Story::class);

        $this->signIn(create(\App\User::class))
             ->json('PATCH', route('stories.update', $story), [
                'name' => 'Fancy quest line',
                'body' => 'Thy shall obey the program. Or lose them gains.',
             ])->assertStatus(403);
    }

    /** @test */
    public function automaticallyAssignsCurrentUserAsCreator()
    {
        $this->signIn($user = create(\App\User::class))
             ->json('POST', route('stories.store'), [
                'name' => 'Fancy quest line',
                'body' => 'Thy shall obey the program. Or lose them gains.',
             ])->assertSuccessful();

        $this->assertDatabaseHas('stories', [
            'name' => 'Fancy quest line',
            'body' => 'Thy shall obey the program. Or lose them gains.',
            'creator_id' => $user->id
        ]);
    }
}
