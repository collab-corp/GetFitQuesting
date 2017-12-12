<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\PersonalAccessClient;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        if (PersonalAccessClient::count() === 0) {
            Artisan::call('passport:install');
        }
    }

    /** @test */
    public function anApiKeyIsGeneratedUponRegistration()
    {
        $result = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])->assertSuccessful()->json();

        $this->assertArrayHasKey('access_token', $result);
        $this->assertNotEmpty($result['access_token']);
    }

    /** @test */
    public function anApiKeyIsGeneratedUponLogin()
    {
        create(User::class, ['email' => 'john@example.com', 'password' => bcrypt('secret')]);

        $result = $this->post('/api/login', [
            'email' => 'john@example.com',
            'password' => 'secret',
        ])->assertSuccessful()->json();

        $this->assertArrayHasKey('access_token', $result);
        $this->assertNotEmpty($result['access_token']);
    }

    /** @test */
    public function canLogout()
    {
        $user = create(User::class);
        $token = $user->createToken('auth')->token;
        $this->signIn($user);

        $this->post('/api/logout')
             ->assertSuccessful();
    }
}
