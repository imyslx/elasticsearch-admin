<?php

return [

    'default' => env('DB_CONNECTION', 'elasticsearch'),
    'connections' => [
        'elasticsearch' => [
            'host' => env('DB_HOST', '127.0.0.1:9200')
        ],
    ],
    'migrations' => 'migrations'
];
