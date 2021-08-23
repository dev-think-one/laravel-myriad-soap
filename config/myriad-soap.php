<?php

return [
    // https://bugs.php.net/bug.php?id=60329
    'use_http_version_1' => true,

    'options' => [
        'location' => env('MYRIAD_SOAP_LOCATION', 'http://00.00.00.00:1234/soap'),
        'uri'      => env('MYRIAD_SOAP_URI', 'forge'),

        // Stuff for development.
        'trace'          => env('MYRIAD_SOAP_LOGIN', true),
        'exceptions'     => env('MYRIAD_SOAP_LOGIN', true),
        'soap_version'   => env('MYRIAD_SOAP_VERSION', SOAP_1_1),
        'cache_wsdl'     => 0
        /*WSDL_CACHE_NONE*/,
        'features'       => 1
        /*SOAP_SINGLE_ELEMENT_ARRAYS*/,

        // Auth credentials for the SOAP request.
        'login'          => env('MYRIAD_SOAP_LOGIN'),
        'password'       => env('MYRIAD_SOAP_PASSWORD'),

        // Proxy url.
        'proxy_host'     => env('MYRIAD_SOAP_PROXY_HOST'),
        // Do not add the schema here (http or https). It won't work.
        'proxy_port'     => env('MYRIAD_SOAP_PROXY_PORT'),

        // Auth credentials for the proxy.
        'proxy_login'    => env('MYRIAD_SOAP_PROXY_LOGIN'),
        'proxy_password' => env('MYRIAD_SOAP_PROXY_PASSWORD'),
    ],
];
