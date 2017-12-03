<?php

namespace Tests\Unit;

use App\Jobs\User\ScrapOldAvatar;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus as Job;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function itScrapsTheOldAvatar() 
    {
    	Storage::fake('s3');

        $avatar = UploadedFile::fake()->image('avatar.jpg')->store('avatars', 's3');
    	$user = create(User::class, ['avatar' => $avatar]);

    	$user->update([
    		'avatar' => 'https://www.gravatar.com/avatar/d5570db0d14ecdc8b629e6d03507d577.jpg?s=200&d=mm'
    	]);

    	Storage::disk('s3')->assertMissing($avatar);
    } 
}
