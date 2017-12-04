<?php

namespace Tests;

include __DIR__."/helpers.php";
use App\Admin;
use App\Exceptions\Handler;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        if (empty(Admin::emails())) {
            config([
                'admin.emails' => [faker()->safeEmail()]
            ]);
        }
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[InMemoryDatabase::class])) {
            $this->useInMemoryDatabase();
        }

        return parent::setupTraits();
    }

    protected function signIn($user = null)
    {
        $user = $user ?? create(User::class);

        Passport::actingAs($user);

        return $this;
    }

    protected function asFirstAdmin()
    {
        $admin = create(\App\User::class, ['email' => array_first(Admin::emails())]);

        return $this->signIn($admin);
    }

    protected function enableExceptionHandling()
    {
        $this->app->forgetInstance(ExceptionHandler::class);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }
            public function report(\Exception $e)
            {
            }
            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }
}
