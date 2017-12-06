<?php

namespace Tests\Unit;

use App\Notifications\Team\TeamDisbanded;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function disbandingATeamNotifiesAllMembers()
    {
        Notification::fake();

        $team = create(Team::class);

        $team->users()->save($member = create(\App\User::class));

        $team->delete();

        Notification::assertSentTo($member, TeamDisbanded::class, function ($notification) use($team) {
            return $notification->team->is($team);
        });
    }
}
