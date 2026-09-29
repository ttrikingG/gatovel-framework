<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Transport
    |--------------------------------------------------------------------------
    |
    | Define o transport utilizado para envio de e-mails.
    |
    */

    'transport' => $_ENV['MAIL_TRANSPORT'] ?? 'log',

    /*
    |--------------------------------------------------------------------------
    | Log Transport
    |--------------------------------------------------------------------------
    |
    | Configuração utilizada pelo transport de log.
    |
    */

    'log' => [

        'path' => $_ENV['MAIL_LOG_PATH']
            ?? __DIR__ . '/../storage/logs/mail.log',

    ],

];
