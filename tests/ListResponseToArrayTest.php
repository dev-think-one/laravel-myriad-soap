<?php


namespace MyriadSoap\Tests;

use MyriadSoap\MyriadSoap;

class ListResponseToArrayTest extends TestCase
{
    /** @test */
    public function response_list_as_string()
    {
        $response  = '410840;14;bar.tr@pg.com;No';
        $formatted = MyriadSoap::listResponseToArray($response, 3, 'ContactCommunication');
        $this->assertIsArray($formatted);
        $this->assertCount(1, $formatted);
        $this->assertEquals($response, $formatted[0]);
    }

    /** @test */
    public function response_list_as_string_incorrect_separators()
    {
        $response = '410840;14;bar.tr@pg.com;No';

        $formatted = MyriadSoap::listResponseToArray($response, 2, 'ContactCommunication');
        $this->assertIsArray($formatted);
        $this->assertCount(0, $formatted);
    }

    /** @test */
    public function response_list_as_array()
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
        $formatted = MyriadSoap::listResponseToArray($response, 3, 'ContactCommunication');
        $this->assertIsArray($formatted);
        $this->assertCount(4, $formatted);
        $this->assertEquals($response['ContactCommunication'][0], $formatted[0]);
        $this->assertEquals($response['ContactCommunication'][4], $formatted[3]);
    }

    /** @test */
    public function response_list_as_array_wrong_key()
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
        $formatted = MyriadSoap::listResponseToArray($response, 3, 'CommunicationItem');
        $this->assertIsArray($formatted);
        $this->assertCount(0, $formatted);
    }
}
