<?php

return [
    'host' => 'http://127.0.0.1',
    'port' => '7777',
    'user' => 'user',
    'pass' => 'YOUR-PASS-HERE',
    'web_interface' => [
        'enabled' => false,
        'currency' => 'EUR',
        'middleware' => ['web', 'auth'],
        'prefix' => 'electrum',
    ],
];
