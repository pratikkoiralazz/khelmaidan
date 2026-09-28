<?php

return [
    'dashboard' => [
        'port' => env('WEBSOCKETS_PORT', 6001),
    ],
    'apps' => [
        [
            'id' => env('PUSHER_APP_ID', 'khelmaidan_app'),
            'name' => env('APP_NAME', 'KhelMaidan'),
            'key' => env('PUSHER_APP_KEY', 'khelmaidan_key'),
            'secret' => env('PUSHER_APP_SECRET', 'khelmaidan_secret'),
            'path' => env('PUSHER_APP_PATH', ''),
            'capacity' => null,
            'enable_client_messages' => false,
            'enable_statistics' => true,
        ],
    ],
];