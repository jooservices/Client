<?php

namespace JOOservices\XClient;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use JOOservices\XClient\Commands\XClientCommand;

class XClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('xclient')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_xclient_table')
            ->hasCommand(XClientCommand::class);
    }
}
