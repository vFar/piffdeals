<?php

return [
    'default' => [
        'site_key' => env('RECAPTCHA_DEFAULT_SITE_KEY', 'default_site_key'),
        'secret' => env('RECAPTCHA_DEFAULT_SECRET_KEY', 'default_secret_key'),
        'options' => [
            'timeout' => 30,
        ],
    ],

    'v2' => [
        'site_key' => env('RECAPTCHA_V2_SITE_KEY', 'default_v2_site_key'),
        'secret' => env('RECAPTCHA_V2_SECRET_KEY', 'default_v2_secret_key'),
        'options' => ['timeout' => 30],
    ],

    'v3' => [
        'site_key' => env('RECAPTCHA_V3_SITE_KEY', 'default_v3_site_key'),
        'secret' => env('RECAPTCHA_V3_SECRET_KEY', 'default_v3_secret_key'),
        'options' => [
            'timeout' => 30,
        ],
    ],
];

