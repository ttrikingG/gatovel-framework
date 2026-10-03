<?php

$allowedOrigins = array_values(
    array_filter(
        array_map(
            'trim',
            explode(
                ',',
                $_ENV['CORS_ALLOWED_ORIGINS'] ?? ''
            )
        )
    )
);

$allowCredentials = filter_var(
    $_ENV['CORS_ALLOW_CREDENTIALS'] ?? false,
    FILTER_VALIDATE_BOOL
);

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | Origens autorizadas a acessar a aplicação através de requisições
    | cross-origin.
    |
    */

    'allowed_origins' => $allowedOrigins,

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    */

    'allowed_methods' => [

        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'OPTIONS',

    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    */

    'allowed_headers' => [

        'Content-Type',
        'Authorization',
        'Accept',

    ],

    /*
    |--------------------------------------------------------------------------
    | Allow Credentials
    |--------------------------------------------------------------------------
    |
    | Permite o envio de credenciais em requisições cross-origin,
    | como cookies e sessões.
    |
    */

    'allow_credentials' => $allowCredentials,

];
