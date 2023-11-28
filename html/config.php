<?php

header('Access-Control-Allow-Origin: *');
/* header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']); */
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Max-Age: 1000');

header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

$config = [
    'debug' => true,
    'displayErrorDetails' => true,
    'determineRouteBeforeAppMiddleware' => true,
    'addContentLengthHeader' => true,
    'layout_path' => './layout/',
    'db' => [
        "server" => 'localhost',
        "username" => 'ashcorp_emers',
        "password" => "B6~@3@VQY\&z6'G-",
        "database" => 'ashcorp_emers'
    ],
    'mail' => [
        "server" => 'mail.ashcorp.co',
        "username" => 'info@e-mersoutsourcing.com'
    ],
    'site' => [
        "name" => 'E-mers outsourcing'
    ]
];
