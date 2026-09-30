<?php

use app\providers\MailServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | Nome da aplicação.
    |
    */

    'name' => $_ENV['APP_NAME'] ?? 'Gatovel',

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | Ambiente atual da aplicação.
    |
    | Exemplos:
    | local
    | development
    | production
    |
    */

    'env' => $_ENV['APP_ENV'] ?? 'production',

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Controla se informações detalhadas de erro podem ser exibidas.
    |
    */

    'debug' => filter_var(
        $_ENV['APP_DEBUG'] ?? false,
        FILTER_VALIDATE_BOOL
    ),

    /*
    |--------------------------------------------------------------------------
    | Application Providers
    |--------------------------------------------------------------------------
    |
    | Providers carregados durante a inicialização da aplicação.
    |
    */

    'providers' => [

        MailServiceProvider::class,

    ],

];
