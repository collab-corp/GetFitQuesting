<?php

namespace Tests;

trait InMemoryDatabase
{
    public function useInMemoryDatabase()
    {
        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');
    }
}
