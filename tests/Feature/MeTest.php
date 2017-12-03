<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

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

    /** @test */
    public function canUpdateMyDetails()
    {
        $user = create(\App\User::class);

        $this->signIn($user)
             ->json('PATCH', route('me.update'), [
                'email' => 'john@example.com',
                'name' => 'John Doe',
                'password' => 'secret'
            ])
             ->assertSuccessful()
             ->assertSee('john@example.com')
             ->assertSee('John Doe');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'john@example.com',
            'name' => 'John Doe'
        ]);

        $this->assertTrue(Hash::check('secret', $user->fresh()->password));
    }

    /** @test */
    public function canUpdateMyAvatar()
    {
        Storage::fake('s3');

        $user = create(\App\User::class, ['avatar' => 'fake-avatar.jpg']);

        $this->signIn($user)
             ->json('PATCH', route('me.update'), [
                'avatar' => UploadedFile::fake()->image('avatar.jpg')
            ])->assertSuccessful();

        $this->assertNotNull($user->fresh()->avatar);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'avatar' => 'fake-avatar.jpg'
        ]);
    }

    /** @test */
    public function canDestroyMyUser()
    {
        $this->signIn($user = create(\App\User::class));

        $this->json('DELETE', route('me.destroy'))
             ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => now()
        ]);
    }
}
