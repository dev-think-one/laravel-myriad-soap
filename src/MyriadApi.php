<?php


namespace MyriadSoap;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use MyriadSoap\Endpoints\FunctionsSet;

class MyriadApi
{
    protected MyriadSoapClient $client;

    /**
     * MyriadManager constructor.
     *
     * @param  MyriadSoapClient  $client
     */
    public function __construct(MyriadSoapClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param  string  $class
     *
     * @return mixed
     * @throws MyriadSoapException
     */
    public function functionsSet(string $class): FunctionsSet
    {
        if (!is_subclass_of($class, FunctionsSet::class)) {
            throw new MyriadSoapException('class should extend FunctionsSet');
        }

        return new $class($this);
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return array|mixed
     * @throws MyriadSoapException
     */
    public function __call($method, $arguments)
    {
        if (strlen($method) > 5
            && Str::startsWith($method, 'SOAP_')) {
            if (Str::endsWith($method, '_List')) {
                $method = Str::beforeLast($method, '_List');

                return $this->listResponseToArray(
                    $this->call($method, $arguments[0] ?? []),
                    $arguments[1] ?? 0,
                    $arguments[2] ?? Str::singular(Str::after($method, 'SOAP_get'))
                );
            } else {
                return $this->call($method, $arguments[0] ?? []);
            }
        }

        throw new \BadMethodCallException("Method {$method} not exists");
    }

    /**
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed|array
     * @throws MyriadSoapException
     */
    public function call(string $method, array $parameters = [])
    {
        $response = $this->client->__soapCall($method, $this->makeSoapParams($parameters));

        if ($this->isFault($response)) {
            throw new MyriadSoapException($this->faultString($response), $method, $parameters);
        }

        if ((bool) Config::get('myriad-soap.format_response', false)) {
            $response = json_decode(json_encode($response), true);
        }

        return $response;
    }

    /**
     * Convert possibles myriad responses formats to array.
     *
     * @param  mixed  $response
     * @param  string  $wrapperKey
     * @param  int  $separatorsCount
     * @return array
     */
    public function listResponseToArray(mixed $response, int $separatorsCount = 0, string $wrapperKey = 'Items'): array
    {
        $formattedArray = [];
        if (is_string($response) && Str::substrCount($response, ';') == $separatorsCount) {
            $formattedArray[] = $response;
        } elseif (is_array($response)
                  && isset($response[$wrapperKey])
                  && is_array($response[$wrapperKey])) {
            foreach ($response[$wrapperKey] as $listItem) {
                $formattedArray = array_merge($formattedArray, $this->listResponseToArray($listItem, $separatorsCount));
            }
        }

        return $formattedArray;
    }

    /**
     * @param  array  $params
     *
     * @return array
     */
    protected function makeSoapParams(array $params)
    {
        $data = [];

        foreach ($params as $key => $value) {
            $data[] = new \SoapParam($value, $key);
        }

        return $data;
    }

    /**
     * @param  mixed|array  $response
     *
     * @return bool
     */
    protected function isFault($response): bool
    {
        return is_array($response) && isset($response['faultcode']);
    }

    /**
     * @param  array  $response
     *
     * @return string
     */
    protected function faultString(array $response): string
    {
        return 'MyriadSoapError ['.($response['faultcode'] ?? '-').']: '.($response['faultstring'] ?? '');
    }


    /**
     * Only for testing purpose
     *
     * @return \Mockery\Mock
     * @throws MyriadSoapException
     */
    public function mockClient()
    {
        if (class_exists('\Mockery')) {
            $mock         = \Mockery::mock(get_class($this->client));
            $this->client = $mock;

            return $this->client;
        }

        throw new MyriadSoapException('mockery not installed');
    }
}
