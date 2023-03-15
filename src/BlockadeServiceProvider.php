<?php

namespace Cjmellor\Blockade;

use Cjmellor\Blockade\Console\Commands\UnblockUserCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BlockadeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('blockade')
            ->hasCommand(UnblockUserCommand::class)
            ->hasConfigFile()
            ->hasMigration('create_blocks_table');
    }
}
