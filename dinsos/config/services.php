<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
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

    'geocoding' => [
        'endpoint' => env(
            'GEOCODING_ENDPOINT',
            'https://nominatim.openstreetmap.org/search'
        ),
        'ca_bundle' => env('GEOCODING_CA_BUNDLE') ?: (
            PHP_OS_FAMILY === 'Windows'
                ? dirname(dirname(PHP_BINARY)).'/apache/bin/curl-ca-bundle.crt'
                : null
        ),
        'user_agent' => env(
            'GEOCODING_USER_AGENT',
            'ProjcetDinsos/1.0'
        ),
        'email' => env('GEOCODING_CONTACT_EMAIL'),
    ],

];
