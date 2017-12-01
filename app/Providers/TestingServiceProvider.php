<?php

namespace App\Providers;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert as PHPUnit;

class TestingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTestResponseMacros();
    }
    
    public function registerTestResponseMacros()
    {
        TestResponse::macro('assertCount', function ($excepted) {
            PHPUnit::assertCount($excepted, $this->decodeResponseJson());

            return $this;
        });
    }
}
