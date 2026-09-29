<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Configura o sistema de logs da aplicação.
    |
    */

    'enabled' => filter_var(
        $_ENV['LOG_ENABLED'] ?? true,
        FILTER_VALIDATE_BOOL
    ),

    /*
    |--------------------------------------------------------------------------
    | Log Path
    |--------------------------------------------------------------------------
    |
    | Arquivo onde os eventos e erros da aplicação serão registrados.
    |
    */

    'path' => $_ENV['LOG_PATH']
        ?? __DIR__ . '/../storage/logs/gatovel.log',

];