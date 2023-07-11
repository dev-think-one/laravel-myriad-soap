<?php

namespace MyriadSoap\Tests;

use MyriadSoap\ServiceProvider;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function provider_is_deferrable()
    {
        $provider = new ServiceProvider($this->app);

        $this->assertIsArray($provider->provides());
        $this->assertCount(1, $provider->provides());
        $this->assertEquals('myriad_soap', $provider->provides()[0]);
    }
}
