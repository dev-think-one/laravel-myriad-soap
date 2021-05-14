<?php


namespace MyriadSoap;

use Illuminate\Support\Facades\Facade;

class MyriadSoap extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'myriad_soap';
    }
}
