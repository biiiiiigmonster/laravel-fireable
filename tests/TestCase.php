<?php

namespace BiiiiiigMonster\Fireable\Tests;

use BiiiiiigMonster\Fireable\FiresServiceProvider;
use BiiiiiigMonster\Fireable\Tests\Models\Phone;
use BiiiiiigMonster\Fireable\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    private Migration $migration;

    protected function getPackageProviders($app)
    {
        return [
            FiresServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->migration->down();
        $this->migration->up();
    }

    protected function destroyDatabaseMigrations()
    {
//        $this->migration->down();
    }

    protected function defineDatabaseSeeders()
    {
        User::withoutEvents(fn () => User::factory(20)->create());
        Phone::withoutEvents(fn () => Phone::factory(20)->create());
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.connections.mysql.prefix', 'fireable_test_');

        Schema::defaultStringLength(191);
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'BiiiiiigMonster\\Fireable\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        $this->migration = include __DIR__ . '/../database/migrations/create_fireable_test_table.php';
    }
}
