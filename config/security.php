<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    */

    'headers' => [

        'content_type_options' => 'nosniff',

        'referrer_policy'
            => 'strict-origin-when-cross-origin',

        'frame_options' => 'DENY',

        'permissions_policy' => implode(
            ', ',
            [
                'camera=()',
                'microphone=()',
                'geolocation=()',
                'payment=()',
                'usb=()',
            ]
        ),

    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    */

    'csp' => [

        'enabled' => filter_var(
            $_ENV['SECURITY_CSP_ENABLED']
                ?? true,
            FILTER_VALIDATE_BOOL
        ),

    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Strict Transport Security
    |--------------------------------------------------------------------------
    |
    | HSTS só deve ser enviado quando a requisição estiver realmente
    | utilizando HTTPS.
    |
    */

    'hsts' => [

        'enabled' => filter_var(
            $_ENV['SECURITY_HSTS_ENABLED']
                ?? true,
            FILTER_VALIDATE_BOOL
        ),

        'max_age' => max(
            0,
            (int) (
                $_ENV['SECURITY_HSTS_MAX_AGE']
                    ?? 31536000
            )
        ),

        'include_subdomains' => filter_var(
            $_ENV['SECURITY_HSTS_INCLUDE_SUBDOMAINS']
                ?? true,
            FILTER_VALIDATE_BOOL
        ),

        'preload' => filter_var(
            $_ENV['SECURITY_HSTS_PRELOAD']
                ?? false,
            FILTER_VALIDATE_BOOL
        ),

    ],

];
