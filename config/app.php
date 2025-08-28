<?php

return [
    'app_name' => 'KARMAPRENEUR.IN',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => (getenv('APP_DEBUG') ?: '0') === '1',
    'base_url' => getenv('APP_URL') ?: '/',

    // Security
    'csrf_secret' => getenv('CSRF_SECRET') ?: 'change-this-csrf-secret',
    'session_name' => 'karmapreneur_sid',

    // UPI / Payments
    'merchant_name' => 'Karmapreneur',
    'merchant_vpa' => getenv('MERCHANT_VPA') ?: 'merchant@upi',

    // Media tokens
    'media_token_secret' => getenv('MEDIA_TOKEN_SECRET') ?: 'change-this-media-secret',
    'media_token_ttl_seconds' => 300,

    // SMTP (placeholders)
    'smtp' => [
        'host' => getenv('SMTP_HOST') ?: 'smtp.example.com',
        'port' => (int)(getenv('SMTP_PORT') ?: 587),
        'user' => getenv('SMTP_USER') ?: '',
        'pass' => getenv('SMTP_PASS') ?: '',
        'from' => getenv('SMTP_FROM') ?: 'no-reply@karmapreneur.in',
    ],
];

