<?php

namespace App\Providers;

require __DIR__.'/../helpers.php';

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Grab a random element from the database.
        QueryBuilder::macro('random', function () {
            return $this->inRandomOrder()->take(1)->get()->first();
        });

        Validator::extend('slug', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'development', 'testing')) {
            $this->app->register('App\Providers\TestingServiceProvider');
        }
    }
}
