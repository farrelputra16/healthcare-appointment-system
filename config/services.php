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

    'resend' => [
        'key' => env('RESEND_KEY'),
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
    'payment' => [
        'api_key' => env('PAYMENT_API_KEY', 'cuXBMQtMy5bhE2oifScKVLWuxniG6fZZ'),
        'base_url' => env('PAYMENT_BASE_URL', 'http://payment-dummy.doovera.com/api/v1'),
        'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', 'ePXj9V9xK2dVwKTcXVfwgD992RVCSQP9'),
        'expired_hours' => env('PAYMENT_EXPIRED_HOURS', 24),
        'consultation_fee' => env('CONSULTATION_FEE', 50000), // Biaya Konsultasi Dasar
    ],

];
