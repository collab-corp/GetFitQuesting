<?php

namespace Tests;

include __DIR__."/helpers.php";
use App\Exceptions\Handler;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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
