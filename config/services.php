<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'tinymce' => [
        'api_key' => env('TINYMCE_API_KEY', env('TINEYMCE_API_KEY')),
    ],

    'stripe' => [
        'donate_url' => env('STRIPE_DONATE_URL'),
    ],

    'weather' => [
        'city' => env('WEATHER_CITY_LABEL', 'Sindh'),
        'latitude' => env('WEATHER_LATITUDE', 24.8607),
        'longitude' => env('WEATHER_LONGITUDE', 67.0011),
        'timezone' => env('WEATHER_TIMEZONE', 'Asia/Karachi'),
        'endpoint' => env('WEATHER_ENDPOINT', 'https://api.open-meteo.com/v1/forecast'),
    ],

];
