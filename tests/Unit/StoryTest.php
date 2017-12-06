<?php

namespace Tests\Unit;

use App\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canListStoriesEnrolledByAUser()
    {
        $user = create(\App\User::class);

        $story = create(Story::class);
        $user->stories()->attach($story);

        $stories = Story::enrolledBy($user)->get();

        $this->assertCount(1, $stories);
        $this->assertTrue($stories->contains($story));
    }
}
