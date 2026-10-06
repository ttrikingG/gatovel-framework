<?php

use app\providers\MailServiceProvider;
use nucleo\middleware\CorsMiddleware;
use nucleo\middleware\SecurityHeadersMiddleware;

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

    /*
    |--------------------------------------------------------------------------
    | Global Middlewares
    |--------------------------------------------------------------------------
    |
    | Middlewares executados em todas as requisições da aplicação.
    |
    | Esses middlewares envolvem todo o ciclo da requisição e podem
    | executar lógica antes e depois do roteamento e do controller.
    |
    */

    'middlewares' => [

        SecurityHeadersMiddleware::class,
        CorsMiddleware::class,

    ],

];
