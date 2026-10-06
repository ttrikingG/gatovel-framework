<?php

$defaultPath =
    __DIR__
    . '/../storage/logs/gatovel.log';

$configuredPath = $_ENV['LOG_PATH']
    ?? null;

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

    'path' => is_string($configuredPath)
        && trim($configuredPath) !== ''
            ? $configuredPath
            : $defaultPath,

];
