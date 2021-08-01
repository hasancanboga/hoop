<?php

return [
    'version' => 'latest',
    'region' => 'eu-central-1',
    'credentials' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ]
];
