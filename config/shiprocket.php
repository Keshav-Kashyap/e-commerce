<?php

return [
    'base_url' => env('SHIPROCKET_BASE_URL', 'https://apiv2.shiprocket.in/v1/external'),
    'email' => env('SHIPROCKET_EMAIL'),
    'password' => env('SHIPROCKET_PASSWORD'),
    'package' => [
        'length' => (float) env('SHIPROCKET_PACKAGE_LENGTH', 10),
        'breadth' => (float) env('SHIPROCKET_PACKAGE_BREADTH', 10),
        'height' => (float) env('SHIPROCKET_PACKAGE_HEIGHT', 10),
        'weight' => (float) env('SHIPROCKET_PACKAGE_WEIGHT', 0.5),
    ],
];