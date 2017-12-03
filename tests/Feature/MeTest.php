<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canSeeMyUserDetails()
    {
        $user = create(\App\User::class, ['email' => 'john@example.com']);

        $this->signIn($user)
             ->json('GET', route('me'))
             ->assertSuccessful()
             ->assertSee('john@example.com');
    }

    /** @test */
    public function canLoadRelationsOntoMyUser()
    {
        $user = create(\App\User::class);
        $user->teams()->save(create(\App\Team::class, ['name' => 'Team blue']));

        $this->signIn($user)
             ->json('GET', route('me'), ['relations' => ['teams']])
             ->assertSuccessful()
             ->assertSee('Team blue');
    }

    /** @test */
    public function cannotEagerLoadInvalidRelations()
    {
        $user = create(\App\User::class);

        $this->signIn($user)
             ->json('GET', route('me'), ['relations' => ['invalid-relation']])
             ->assertJsonValidationErrors('relations');
    }
}
