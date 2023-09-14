<?php

namespace Thunk\Verbs\Tests;

use Glhd\Bits\Support\BitsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use InterNACHI\Modular\Support\ModularServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Thunk\Verbs\VerbsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            function (string $modelName) {
                return str($modelName)
                    ->replace('\\Models\\', '\\Database\\Factories\\')
                    ->append('Factory')
                    ->toString();
            }
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ModularServiceProvider::class, // This must register first
            VerbsServiceProvider::class,
            BitsServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $events = include __DIR__.'/../database/migrations/create_verb_events_table.php.stub';
        $events->up();

        $snapshots = include __DIR__.'/../database/migrations/create_verb_snapshots_table.php.stub';
        $snapshots->up();
    }
}
