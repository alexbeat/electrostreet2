<?php
return [
    'paths' => ['api/*', 'backend/*', '*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://vseslav.name','https://electrostreet.ru'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];