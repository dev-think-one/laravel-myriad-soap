# Laravel: Myriad SOAP

Unofficial web integration with Myriad 5.1

## Installation

You can install the package via composer:

```bash
composer require yaroslawww/laravel-myriad-soap

php artisan vendor:publish --provider="MyriadSoap\ServiceProvider" --tag="config"
```

## Usage

Direct call via facade:

```injectablephp
$result = MyriadSoap::SOAP_getContactCommunications(['Contact_ID' => 1234]);
/*
[
 "ContactCommunication" => [
   "123456;12;01234 567 890;Yes",
   "123457;14;me@test.co.uk;No",
 ],
]
 */
```

Using feature sets that allow you to wrap your own business logic (each class should `extends FunctionsSet`)

```injectablephp
use MyriadSoap\Endpoints\FunctionsSet;

class MyContactFunctions extends FunctionsSet {

    public function getContactCommunications( int $customerNumber ) {
        return $this->api->call(
            'SOAP_getContactCommunications',
            [
                'Contact_ID' => $customerNumber,
            ]
        );
    }
}

$result =  MyriadSoap::functionsSet(MyContactFunctions::class)->getContactCommunications(1234);
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
