<?php

namespace JOOservices\XClient\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as Orchestra;
use JOOservices\XClient\XClientServiceProvider;

class TestCase extends Orchestra
{
    use WithFaker;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'JOOservices\\XClient\\Database\\Factories\\'
                .class_basename($modelName)
                .'XClientFactory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            XClientServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_xlogger_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_xclient_table.php.stub';
        $migration->up();
    }
}
