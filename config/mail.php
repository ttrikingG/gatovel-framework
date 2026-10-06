<?php

$defaultLogPath =
    __DIR__
    . '/../storage/logs/mail.log';

$configuredLogPath =
    $_ENV['MAIL_LOG_PATH']
    ?? null;

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Transport
    |--------------------------------------------------------------------------
    |
    | Define o transport utilizado para envio de e-mails.
    |
    */

    'transport' => $_ENV['MAIL_TRANSPORT']
        ?? 'log',

    /*
    |--------------------------------------------------------------------------
    | Log Transport
    |--------------------------------------------------------------------------
    |
    | Configuração utilizada pelo transport de log.
    |
    */

    'log' => [

        'path' => is_string(
            $configuredLogPath
        )
            && trim(
                $configuredLogPath
            ) !== ''
                ? $configuredLogPath
                : $defaultLogPath,

    ],

];
