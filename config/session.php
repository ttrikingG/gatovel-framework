<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Session Name
    |--------------------------------------------------------------------------
    |
    | Nome do cookie utilizado pela sessão.
    |
    */

    'name' => $_ENV['SESSION_NAME']
        ?? 'gatovel_session',

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Tempo máximo, em minutos, utilizado pelo PHP para manter os dados
    | da sessão disponíveis no armazenamento.
    |
    | Este valor não representa o tempo máximo de inatividade.
    |
    */

    'lifetime' => max(
        1,
        (int) ($_ENV['SESSION_LIFETIME'] ?? 120)
    ),

    /*
    |--------------------------------------------------------------------------
    | Idle Timeout
    |--------------------------------------------------------------------------
    |
    | Tempo máximo, em minutos, que uma sessão pode permanecer sem
    | atividade antes de seu estado ser invalidado.
    |
    */

    'idle_timeout' => max(
        1,
        (int) ($_ENV['SESSION_IDLE_TIMEOUT'] ?? 30)
    ),

    /*
    |--------------------------------------------------------------------------
    | Cookie Path
    |--------------------------------------------------------------------------
    */

    'path' => $_ENV['SESSION_PATH']
        ?? '/',

    /*
    |--------------------------------------------------------------------------
    | Cookie Domain
    |--------------------------------------------------------------------------
    |
    | Vazio utiliza o domínio atual.
    |
    */

    'domain' => $_ENV['SESSION_DOMAIN']
        ?? '',

    /*
    |--------------------------------------------------------------------------
    | Secure Cookie
    |--------------------------------------------------------------------------
    |
    | Deve ser true em produção quando a aplicação utiliza HTTPS.
    | Em desenvolvimento HTTP local normalmente será false.
    |
    */

    'secure' => filter_var(
        $_ENV['SESSION_SECURE'] ?? false,
        FILTER_VALIDATE_BOOL
    ),

    /*
    |--------------------------------------------------------------------------
    | HTTP Only
    |--------------------------------------------------------------------------
    |
    | Impede acesso ao cookie de sessão através de JavaScript.
    |
    */

    'http_only' => filter_var(
        $_ENV['SESSION_HTTP_ONLY'] ?? true,
        FILTER_VALIDATE_BOOL
    ),

    /*
    |--------------------------------------------------------------------------
    | SameSite
    |--------------------------------------------------------------------------
    |
    | Valores suportados:
    | Lax
    | Strict
    | None
    |
    */

    'same_site' => $_ENV['SESSION_SAME_SITE']
        ?? 'Lax',

];
