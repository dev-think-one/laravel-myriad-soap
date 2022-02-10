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
            } elseif (Str::endsWith($method, '_Collection')) {
                $method = Str::beforeLast($method, '_Collection');

                return $this->listResponseToCollection(
                    $this->call($method, $arguments[0] ?? []),
                    $arguments[1] ?? [],
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
     * @param  int  $separatorsCount
     * @param  string  $wrapperKey
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
     * Convert possibles myriad responses formats to collection.
     *
     * @param  mixed  $response
     * @param  array  $keys
     * @param  string  $wrapperKey
     * @return \Illuminate\Support\Collection
     */
    public function listResponseToCollection(mixed $response, array $keys, string $wrapperKey = 'Items'): \Illuminate\Support\Collection
    {
        $array = $this->listResponseToArray($response, !empty($keys) ? count($keys) - 1 : 0, $wrapperKey);

        return collect($array)
            ->map(function ($communication) use ($keys) {
                $communicationParts = collect(explode(';', $communication))
                    ->map(fn ($part) => trim($part))
                    ->filter();
                if ($communicationParts->count() == count($keys)) {
                    $item = [];
                    $counter = 0;
                    foreach ($keys as $key => $callback) {
                        $value = $communicationParts->get($counter);
                        if (is_callable($callback)) {
                            $item[$key] = call_user_func($callback, $value);
                        } else {
                            $item[$callback] = $value;
                        }
                        $counter++;
                    }

                    return $item;
                }

                return null;
            })->filter();
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
