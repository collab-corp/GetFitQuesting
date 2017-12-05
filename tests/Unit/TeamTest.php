<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function disbandingATeamNotifiesAllMembers()
    {
        // create team
        //
        // disband team
        //
        // assert team members except the leader has been notified.
        
        $this->markTestIncomplete();
    }
}
