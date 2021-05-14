<?php

namespace MyriadSoap\Tests;

use MyriadSoap\Endpoints\ContactFunctions;
use MyriadSoap\MyriadSoap;

class ContactFunctionsSetTest extends TestCase
{

    /** @test */
    public function SOAP_getContactDetails()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactDetails', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn([ 'test' => 'response' ]);

        $result = MyriadSoap::functionsSet(ContactFunctions::class)->getContactDetails(1234);

        $this->assertArrayHasKey('test', $result);
        $this->assertEquals('response', $result['test']);
    }

    /** @test */
    public function getContactIDForInstitution()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactIDForInstitution', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn([ 'test' => 'response' ]);

        $result = MyriadSoap::functionsSet(ContactFunctions::class)->getContactIDForInstitution(1234);

        $this->assertArrayHasKey('test', $result);
        $this->assertEquals('response', $result['test']);
    }

    /** @test */
    public function getContactDemographics()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactDemographics', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn([ 'test' => 'response' ]);

        $result = MyriadSoap::functionsSet(ContactFunctions::class)->getContactDemographics(1234);

        $this->assertArrayHasKey('test', $result);
        $this->assertEquals('response', $result['test']);
    }

    /** @test */
    public function getContactMarketingRules()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactMarketingRules', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn([ 'test' => 'response' ]);

        $result = MyriadSoap::functionsSet(ContactFunctions::class)->getContactMarketingRules(1234);

        $this->assertArrayHasKey('test', $result);
        $this->assertEquals('response', $result['test']);
    }
}
