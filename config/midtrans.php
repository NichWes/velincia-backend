<?php

return [
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    'notification_url' => env('MIDTRANS_NOTIFICATION_URL'),
    'finish_url' => env('MIDTRANS_FINISH_URL'),
    'unfinish_url' => env('MIDTRANS_UNFINISH_URL'),
    'error_url' => env('MIDTRANS_ERROR_URL'),
];