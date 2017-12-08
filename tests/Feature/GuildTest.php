<?php

namespace Tests\Feature;

use App\Guild;
use App\Jobs\Guild\ResizeBannerImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function bannerIsUploadedIfIncluded()
    {
        Storage::fake('s3');

        $this->signIn();

        $this->json('POST', route('guilds.store'), [
            'name' => 'Gladiators inc',
            'description' => 'Totally casual.',
            'banner' => UploadedFile::fake()->image('banner.png', 1280, 1024)
        ])->assertSuccessful();

        $guild = Guild::where('name', 'Gladiators inc')->first();
        $this->assertNotNull($guild->banner);

        Storage::disk('s3')->assertExists($guild->getAttributes()['banner']);
    }

    /** @test */
    function canUpdateBanner() 
    {
    	Storage::fake('s3');

    	$guild = create(Guild::class);

    	$this->signIn($guild->creator);

    	$this->json('PUT', route('guilds.update', $guild), [
    		'banner' => UploadedFile::fake()->image('banner.png', 1280, 1024)
    	])->assertSuccessful();

    	$this->assertNotEquals($guild->banner, $guild->fresh()->banner);
    } 

    /** @test */
    public function canLeaveAGuild()
    {
        $user = create(\App\User::class);

        $guild = create(Guild::class);

        $member = $guild->members()->create(['user_id' => $user->id]);

        $this->signIn($user)
            ->json('DELETE', route('guild.leave', $guild))
            ->assertSuccessful();
    }
}
