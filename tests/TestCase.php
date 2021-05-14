<?php

namespace MyriadSoap\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            \MyriadSoap\ServiceProvider::class,
        ];
    }

    public function defineEnvironment($app)
    {
        // $app['config']->set('myriad-soap.options.location', 'https://test.home');
    }
}
