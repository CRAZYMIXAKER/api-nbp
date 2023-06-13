<?php
return [
    [
        'route' => '/^\/?$/',
        'method' => 'index',
        'controller' => 'App',
        'request_method' => 'GET'
    ],
    [
        'route' => '/^\/currency?$/',
        'method' => 'index',
        'controller' => 'Currency',
        'request_method' => 'GET'
    ],
    [
        'route' => '/^\/converter?$/',
        'method' => 'index',
        'controller' => 'Converter',
        'request_method' => 'GET'
    ],
    [
        'route' => '/^\/converter?$/',
        'method' => 'convert',
        'controller' => 'Converter',
        'request_method' => 'POST'
    ],
    [
        'route' => '/^\/converted-currency-history?$/',
        'method' => 'index',
        'controller' => 'ConvertedCurrencyHistory',
        'request_method' => 'GET'
    ]
];