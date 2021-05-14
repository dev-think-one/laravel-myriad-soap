<?php

namespace MyriadSoap\Tests;

use Illuminate\Database\Eloquent\Model;
use MyriadSoap\Endpoints\ContactFunctions;
use MyriadSoap\MyriadSoap;
use MyriadSoap\MyriadSoapException;

class FunctionsSetTest extends TestCase
{

    /** @test */
    public function functions_set_call()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn([ 'test' => 'response' ]);

        $result = MyriadSoap::functionsSet(ContactFunctions::class)->getContactCommunications(1234);

        $this->assertArrayHasKey('test', $result);
        $this->assertEquals('response', $result['test']);
    }

    /** @test */
    public function functions_set_call_exception()
    {
        $this->expectException(MyriadSoapException::class);

        $result = MyriadSoap::functionsSet(Model::class);
    }
}
