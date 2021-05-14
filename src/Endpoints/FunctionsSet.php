<?php


namespace MyriadSoap\Endpoints;

use MyriadSoap\MyriadApi;

abstract class FunctionsSet
{
    protected MyriadApi $api;

    /**
     * FunctionsSet constructor.
     *
     * @param MyriadApi $api
     */
    public function __construct(MyriadApi $api)
    {
        $this->api = $api;
    }
}
