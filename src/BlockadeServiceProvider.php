<?php

namespace Cjmellor\Blockade;

use Cjmellor\Blockade\Console\Commands\UnblockUserCommand;
use Illuminate\Support\ServiceProvider;

class BlockadeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UnblockUserCommand::class,
            ]);
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/blockade.php' => config_path('blockade.php'),
        ], groups: 'blockade-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], groups: 'blockade-migrations');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/blockade.php', 'blockade'
        );
    }
}
