<?php


namespace MyriadSoap\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MyriadSoap\MyriadSoap;
use MyriadSoap\MyriadSoapException;

class DirectCollectionCallTest extends TestCase
{
    protected function collectionFormat(): array
    {
        return [
            'ContactCommunication_ID' => fn ($i) => (int) $i,
            'DespatchType_ID'         => fn ($i) => (int) $i,
            'ContactCommunication',
            'PrimaryUse' => fn ($i) => $i == 'Yes',
        ];
    }

    /** @test */
    public function direct_call()
    {
        $response = '410840;14;bar.tr@pg.com;No';

        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn($response);

        $result = MyriadSoap::SOAP_getContactCommunications_Collection(['Contact_ID' => 1234], $this->collectionFormat());
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertEquals(Str::beforeLast($response, ';').';', implode(';', $result->first()));
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

        $result = MyriadSoap::SOAP_getContactCommunications_Collection(['Contact_ID' => 1234], $this->collectionFormat(), 'ContactCommunication');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(4, $result);
        $this->assertEquals(Str::beforeLast($response['ContactCommunication'][0], ';').';', implode(';', $result->first()));
        $this->assertEquals(Str::beforeLast($response['ContactCommunication'][4], ';').';1', implode(';', $result->get(3)));
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

        $result = MyriadSoap::SOAP_getContactCommunications_Collection(['Contact_ID' => 1234], $this->collectionFormat());
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(4, $result);
        $this->assertEquals(Str::beforeLast($response['ContactCommunication'][0], ';').';', implode(';', $result->first()));
        $this->assertEquals(Str::beforeLast($response['ContactCommunication'][4], ';').';1', implode(';', $result->get(3)));
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

        $result = MyriadSoap::SOAP_getContactCommunications_Collection(['Contact_ID' => 1234], $this->collectionFormat());
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

    /** @test */
    public function direct_call_soap_exception()
    {
        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getContactCommunications', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn(['faultcode' => 'faultcodeTest', 'faultstring' => 'faultstringTest']);

        $this->expectException(MyriadSoapException::class);
        MyriadSoap::SOAP_getContactCommunications_Collection(['Contact_ID' => 1234], $this->collectionFormat());
    }
}
