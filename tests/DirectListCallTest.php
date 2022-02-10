<?php


namespace MyriadSoap\Tests;

use MyriadSoap\MyriadSoap;
use MyriadSoap\MyriadSoapException;

class DirectListCallTest extends TestCase
{

    /** @test */
    public function direct_call()
    {
        $response  = '410840;14;bar.tr@pg.com;No';

        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn($response);

        $result = MyriadSoap::SOAP_getContactCommunications_List([ 'Contact_ID' => 1234 ], 3);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals($response, $result[0]);
    }

    /** @test */
    public function direct_call_return_array()
    {
        $response = [
            'ContactCommunication' => [
                '410839;12;01279 506193;No',
                '410840;14;bar.tr@pg.com;No',
                '410841;14;foo.tr@pg.com;Yes',
                'WRONG;SEPARATORS,COUNT',
                '410842;12;07825 978 907;Yes',
            ],
        ];

        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn($response);

        $result = MyriadSoap::SOAP_getContactCommunications_List([ 'Contact_ID' => 1234 ], 3, 'ContactCommunication');
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
        $this->assertEquals($response['ContactCommunication'][0], $result[0]);
        $this->assertEquals($response['ContactCommunication'][4], $result[3]);
    }

    /** @test */
    public function direct_call_return_array_guess_key()
    {
        $response = [
            'ContactCommunication' => [
                '410839;12;01279 506193;No',
                '410840;14;bar.tr@pg.com;No',
                '410841;14;foo.tr@pg.com;Yes',
                'WRONG;SEPARATORS,COUNT',
                '410842;12;07825 978 907;Yes',
            ],
        ];

        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn($response);

        $result = MyriadSoap::SOAP_getContactCommunications_List([ 'Contact_ID' => 1234 ], 3);
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
        $this->assertEquals($response['ContactCommunication'][0], $result[0]);
        $this->assertEquals($response['ContactCommunication'][4], $result[3]);
    }

    /** @test */
    public function direct_call_return_array_guess_key_error()
    {
        $response = [
            'WRONG' => [
                '410839;12;01279 506193;No',
                '410840;14;bar.tr@pg.com;No',
                '410841;14;foo.tr@pg.com;Yes',
                'WRONG;SEPARATORS,COUNT',
                '410842;12;07825 978 907;Yes',
            ],
        ];

        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn($response);

        $result = MyriadSoap::SOAP_getContactCommunications_List([ 'Contact_ID' => 1234 ], 3);
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
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
        MyriadSoap::SOAP_getContactCommunications_List([ 'Contact_ID' => 1234 ], 3);
    }
}
