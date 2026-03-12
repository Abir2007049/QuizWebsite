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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'mailtrap' => [
        'token' => env('MAILTRAP_API_TOKEN'),
        'endpoint' => env('MAILTRAP_API_ENDPOINT', 'https://send.api.mailtrap.io/api/send'),
        'from_address' => env('MAILTRAP_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'from_name' => env('MAILTRAP_FROM_NAME', env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel'))),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
