# Laravel: Myriad SOAP

![Packagist License](https://img.shields.io/packagist/l/yaroslawww/laravel-myriad-soap?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/laravel-myriad-soap)](https://packagist.org/packages/yaroslawww/laravel-myriad-soap)
[![Total Downloads](https://img.shields.io/packagist/dt/yaroslawww/laravel-myriad-soap)](https://packagist.org/packages/yaroslawww/laravel-myriad-soap)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/laravel-myriad-soap/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-myriad-soap/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/laravel-myriad-soap/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-myriad-soap/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/laravel-myriad-soap/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-myriad-soap/?branch=master)

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

By default Myriad lists responses has unexpected lists responses, that why will be useful helper:

```php
MyriadSoap::listResponseToArray(
    MyriadSoap::SOAP_getContactCommunications(['Contact_ID' => 1234]), 
    3, 'ContactCommunication'
);
// or
MyriadSoap::SOAP_getContactCommunications_List(['Contact_ID' => 1234], 3, 'ContactCommunication');
// Appropriate key, app will try guess itself:
MyriadSoap::SOAP_getContactCommunications_List(['Contact_ID' => 1234], 3);
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
