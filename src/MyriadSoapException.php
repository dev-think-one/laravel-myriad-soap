<?php


namespace MyriadSoap;

use Throwable;

class MyriadSoapException extends \Exception
{
    protected string $soapMethod;
    protected array $soapParams;

    public function __construct($message = "", string $soapMethod = '', array $soapParams = [], $code = 0, Throwable $previous = null)
    {
        $this->soapMethod = $soapMethod;
        $this->soapParams = $soapParams;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getSoapMethod(): string
    {
        return $this->soapMethod;
    }

    /**
     * @return array
     */
    public function getSoapParams(): array
    {
        return $this->soapParams;
    }
}
