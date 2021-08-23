<?php


namespace MyriadSoap\Tests;

use MyriadSoap\MyriadSoap;
use MyriadSoap\MyriadSoapException;

class DirectCallTest extends TestCase
{

    /** @test */
    public function direct_call()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn([ 'test' => 'response' ]);

        $result = MyriadSoap::SOAP_getContactCommunications([ 'Contact_ID' => 1234 ]);

        $this->assertArrayHasKey('test', $result);
        $this->assertEquals('response', $result['test']);
    }

    /** @test */
    public function direct_call_soap_exception()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn([ 'faultcode' => 'faultcodeTest', 'faultstring' => 'faultstringTest' ]);

        $this->expectException(MyriadSoapException::class);
        MyriadSoap::SOAP_getContactCommunications([ 'Contact_ID' => 1234 ]);
    }

    /** @test */
    public function direct_call_soap_exception_has()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn([ 'faultcode' => 'faultcodeTest', 'faultstring' => 'faultstringTest' ]);

        try {
            MyriadSoap::SOAP_getContactCommunications([ 'Contact_ID' => 1234 ]);
        } catch (MyriadSoapException $e) {
            $this->assertEquals('SOAP_getContactCommunications', $e->getSoapMethod());
            $this->assertEquals(1234, $e->getSoapParams()['Contact_ID']);
        }
    }

    /** @test */
    public function direct_call_exception()
    {
        $this->expectException(\TypeError::class);
        MyriadSoap::SOAP_getContactCommunications('not_array');
    }
}
