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

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    
    // Turnstile is deprecated and removed - using reCAPTCHA only
    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],
    
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY', '6LfequQqAAAAABMcV2XHaBAPjvop2geDre87MQAN'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', '6LfequQqAAAAALDQ7NOMeoeuBROa1rOiM7zaFVSq'),
    ],

    'developer_portal' => [
        'api_key' => env('DEVELOPER_PORTAL_API_KEY'),
    ],

];
