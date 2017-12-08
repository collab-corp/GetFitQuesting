<?php

namespace Tests\Unit\Guild;

use App\Guild;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuildMembersCountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function addingAGuildMemberIncrementsCount()
    {
        $guild = create(Guild::class);

        $guild->members()->create(['user_id' => create(\App\User::class)->id]);

        $this->assertEquals(1, $guild->fresh()->members_count);
    }

    /** @test */
    public function deletingAMemberDecrementsCount()
    {
        $guild = create(Guild::class);

        $member = $guild->members()->create(['user_id' => create(\App\User::class)->id]);

        $member->delete();

        $this->assertEquals(0, $guild->fresh()->members_count);
    }

    /** @test */
    public function changingGuildUpdatesBothGuilds()
    {
        $guild = create(Guild::class);

        $member = $guild->members()->create(['user_id' => create(\App\User::class)->id]);

        $newGuild = create(Guild::class);

        $member->update(['guild_id' => $newGuild->id]);

        $this->assertEquals(0, $guild->fresh()->members_count);
        $this->assertEquals(1, $newGuild->fresh()->members_count);
    }
}
