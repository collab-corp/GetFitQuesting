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
             ->json('GET', route('me.details'))
             ->dump()
             ->assertSee('john@example.com');
    }
}
