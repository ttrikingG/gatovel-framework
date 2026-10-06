<?php

namespace nucleo\errors;

use nucleo\http\Request;
use nucleo\security\Csp;
use Throwable;

class ErrorPageRenderer
{
    public static function render(
        Throwable $exception,
        int $statusCode,
        ?Request $request = null
    ): string {
        $data = self::pageData(
            $statusCode
        );

        $data['cspNonce'] = self::cspNonce(
            $request
        );

        $viewFile = __DIR__
            . '/views/error.php';

        if (!is_file($viewFile)) {
            return self::fallback(
                $statusCode,
                $data['title']
            );
        }

        extract(
            $data,
            EXTR_SKIP
        );

        ob_start();

        require $viewFile;

        $content = ob_get_clean();

        if ($content === false) {
            return self::fallback(
                $statusCode,
                $data['title']
            );
        }

        return $content;
    }

    public static function renderDebug(
        Throwable $exception,
        int $statusCode,
        ?Request $request = null
    ): string {
        $cspNonce = self::cspNonce(
            $request
        );

        $viewFile = __DIR__
            . '/views/debug.php';

        if (!is_file($viewFile)) {
            return self::debugFallback(
                $exception,
                $statusCode
            );
        }

        ob_start();

        require $viewFile;

        $content = ob_get_clean();

        if ($content === false) {
            return self::debugFallback(
                $exception,
                $statusCode
            );
        }

        return $content;
    }

    private static function cspNonce(
        ?Request $request
    ): string {
        if ($request === null) {
            return '';
        }

        if (
            !$request->hasAttribute(
                Csp::NONCE_ATTRIBUTE
            )
        ) {
            return '';
        }

        return Csp::htmlNonce(
            $request
        );
    }

    private static function pageData(
        int $statusCode
    ): array {
        return match ($statusCode) {
            400 => [
                'statusCode' => 400,
                'title' => 'Miau... essa requisição veio estranha.',
                'message' =>
                    'O Gatovel não conseguiu entender a requisição '
                    . 'enviada. Verifique os dados e tente novamente.',
                'terminalCommand' => 'gatovel inspect request',
                'terminalMessage' => 'bad request',
            ],

            401 => [
                'statusCode' => 401,
                'title' => 'Miau... primeiro precisamos saber quem é você.',
                'message' =>
                    'Esta área exige autenticação. '
                    . 'Entre na sua conta e tente novamente.',
                'terminalCommand' => 'gatovel check auth',
                'terminalMessage' => 'unauthorized',
            ],

            403 => [
                'statusCode' => 403,
                'title' => 'Miau... você não pode entrar aqui.',
                'message' =>
                    'Você está autenticado, mas não possui permissão '
                    . 'para acessar este recurso.',
                'terminalCommand' => 'gatovel check permission',
                'terminalMessage' => 'forbidden',
            ],

            404 => [
                'statusCode' => 404,
                'title' => 'Miau... página não encontrada.',
                'message' =>
                    'Parece que esta página escapou pelo telhado. '
                    . 'O Gatovel procurou por todos os cantos, '
                    . 'mas não conseguiu encontrá-la.',
                'terminalCommand' => 'gatovel find page',
                'terminalMessage' => 'route not found',
            ],

            405 => [
                'statusCode' => 405,
                'title' => 'Miau... por aqui não dá para entrar.',
                'message' =>
                    'A página existe, mas esse método de acesso '
                    . 'não é permitido por esta rota.',
                'terminalCommand' => 'gatovel inspect method',
                'terminalMessage' => 'method not allowed',
            ],

            409 => [
                'statusCode' => 409,
                'title' => 'Miau... encontramos um conflito.',
                'message' =>
                    'A requisição entrou em conflito com o estado '
                    . 'atual do recurso.',
                'terminalCommand' => 'gatovel inspect conflict',
                'terminalMessage' => 'resource conflict',
            ],

            419 => [
                'statusCode' => 419,
                'title' => 'Miau... sua sessão perdeu uma vida.',
                'message' =>
                    'A sessão expirou ou o token de segurança '
                    . 'não é mais válido. Tente novamente.',
                'terminalCommand' => 'gatovel check session',
                'terminalMessage' => 'csrf token expired',
            ],

            422 => [
                'statusCode' => 422,
                'title' => 'Miau... alguns dados não passaram na inspeção.',
                'message' =>
                    'Os dados enviados possuem erros de validação. '
                    . 'Revise as informações e tente novamente.',
                'terminalCommand' => 'gatovel validate request',
                'terminalMessage' => 'validation failed',
            ],

            429 => [
                'statusCode' => 429,
                'title' => 'Miau... devagar com as requisições.',
                'message' =>
                    'Muitas requisições foram enviadas em pouco tempo. '
                    . 'Aguarde um momento e tente novamente.',
                'terminalCommand' => 'gatovel check rate',
                'terminalMessage' => 'too many requests',
            ],

            default => [
                'statusCode' => 500,
                'title' => 'Miau... algo caiu do servidor.',
                'message' =>
                    'O Gatovel encontrou um problema inesperado. '
                    . 'Tente novamente em alguns instantes.',
                'terminalCommand' => 'gatovel diagnose',
                'terminalMessage' => 'internal server error',
            ],
        };
    }

    private static function fallback(
        int $statusCode,
        string $title
    ): string {
        $safeStatusCode = htmlspecialchars(
            (string) $statusCode,
            ENT_QUOTES,
            'UTF-8'
        );

        $safeTitle = htmlspecialchars(
            $title,
            ENT_QUOTES,
            'UTF-8'
        );

        return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>{$safeStatusCode} | Gatovel Framework</title>
</head>
<body>
    <h1>{$safeStatusCode}</h1>
    <p>{$safeTitle}</p>
</body>
</html>
HTML;
    }

    private static function debugFallback(
        Throwable $exception,
        int $statusCode
    ): string {
        $safeStatusCode = htmlspecialchars(
            (string) $statusCode,
            ENT_QUOTES,
            'UTF-8'
        );

        $safeType = htmlspecialchars(
            get_class($exception),
            ENT_QUOTES,
            'UTF-8'
        );

        $safeMessage = htmlspecialchars(
            $exception->getMessage(),
            ENT_QUOTES,
            'UTF-8'
        );

        $safeFile = htmlspecialchars(
            $exception->getFile(),
            ENT_QUOTES,
            'UTF-8'
        );

        $safeLine = htmlspecialchars(
            (string) $exception->getLine(),
            ENT_QUOTES,
            'UTF-8'
        );

        return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Exception | Gatovel Framework</title>
</head>
<body>
    <h1>{$safeType}</h1>
    <p>{$safeMessage}</p>
    <p>HTTP {$safeStatusCode}</p>
    <p>{$safeFile}:{$safeLine}</p>
</body>
</html>
HTML;
    }
}
