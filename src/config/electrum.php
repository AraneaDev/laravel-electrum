<?php

return [
    'host'          => 'http://127.0.0.1',
    'port'          => '7777',
    'web_interface' => [
        'enabled'    => false,
        'currency'   => 'EUR',
        'middleware' => ['web', 'auth'],
        'prefix'     => 'electrum',
    ],
];
