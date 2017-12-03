<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canRegisterWithAPhoto()
    {
        Storage::fake('s3');

        $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ])->assertRedirect('/home');

        $user = User::whereEmail('john@example.com')->first();
        $this->assertNotNull($user->avatar);

        Storage::disk('s3')->assertExists($user->getAttributes()['avatar']);
    }
}
