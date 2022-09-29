<?php

namespace BiiiiiigMonster\Fires\Tests;

use BiiiiiigMonster\Fires\FiresServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            FiresServiceProvider::class,
        ];
    }
}
