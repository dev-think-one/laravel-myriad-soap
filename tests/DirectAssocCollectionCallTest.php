<?php


namespace MyriadSoap\Tests;

use Illuminate\Support\Collection;
use MyriadSoap\Exceptions\UnexpectedTypeException;
use MyriadSoap\MyriadSoap;

class DirectAssocCollectionCallTest extends TestCase
{
    protected function collectionFormat(): array
    {
        return [
            'OrderPackageType_ID' => fn ($i) => (int) tap($i, fn () => throw_if(!is_numeric($i), UnexpectedTypeException::class)),
            'OrderPackageType'    => fn ($i)    => (string) $i,
            'OrderPackageCategory',
        ];
    }

    /** @test */
    public function direct_call()
    {
        $response = [
            'OrderPackageType_ID'  => '1',
            'OrderPackageType'     => 'New Order via Web',
            'OrderPackageCategory' => 'New Order',
        ];

        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getOrderPackageTypes', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn($response);

        $result = MyriadSoap::SOAP_getOrderPackageTypes_AssocCollection([], $this->collectionFormat());
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertEquals(1, $result->first()['OrderPackageType_ID']);
        $this->assertIsInt($result->first()['OrderPackageType_ID']);
        $this->assertEquals('New Order via Web', $result->first()['OrderPackageType']);
        $this->assertEquals('New Order', $result->first()['OrderPackageCategory']);
    }

    /** @test */
    public function direct_call_return_array()
    {
        $response = [
            'OrderPackageType' => [
                [
                    'OrderPackageType_ID'  => '1',
                    'OrderPackageType'     => 'New Order via Web',
                    'OrderPackageCategory' => 'New Order',
                ],
                [
                    'OrderPackageType_ID'  => 'Wrong',
                    'OrderPackageType'     => 'New Order via Web',
                    'OrderPackageCategory' => 'New Order',
                ],
                [
                    'OrderPackageType_ID'  => '56',
                    'OrderPackageType'     => 'New Order via Mobile',
                    'OrderPackageCategory' => 'Order',
                ],
                [
                    'OrderPackageType'     => 'Not all fields',
                    'OrderPackageCategory' => 'Order',
                ],
            ],
        ];

        /** @var \Mockery\Mock $mock */
        $mock = MyriadSoap::mockClient();

        $mock->shouldReceive('__soapCall')
             ->with('SOAP_getOrderPackageTypes', \Hamcrest\Core\IsTypeOf::typeOf('array'))
             ->andReturn($response);

        $result = MyriadSoap::SOAP_getOrderPackageTypes_AssocCollection([], $this->collectionFormat(), 'OrderPackageType');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(1, $result->first()['OrderPackageType_ID']);
        $this->assertIsInt($result->first()['OrderPackageType_ID']);
        $this->assertEquals('New Order via Web', $result->first()['OrderPackageType']);
        $this->assertEquals('New Order', $result->first()['OrderPackageCategory']);
        $this->assertEquals(56, $result->get(1)['OrderPackageType_ID']);
        $this->assertIsInt($result->get(1)['OrderPackageType_ID']);
        $this->assertEquals('New Order via Mobile', $result->get(1)['OrderPackageType']);
        $this->assertEquals('Order', $result->get(1)['OrderPackageCategory']);
    }
}
