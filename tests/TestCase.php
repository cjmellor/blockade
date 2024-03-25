<?php

namespace Cjmellor\Blockade\Tests;

use Cjmellor\Blockade\BlockadeServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'Cjmellor\\Blockade\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            BlockadeServiceProvider::class,
        ];
    }
}
