<?php

namespace nucleo\errors;

use Throwable;

class ErrorPageRenderer
{
    public static function render(
        Throwable $exception,
        int $statusCode
    ): string {
        $data = self::pageData(
            $statusCode
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
        int $statusCode
    ): string {
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

    private static function pageData(
        int $statusCode
    ): array {
        return match ($statusCode) {

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

            419 => [
                'statusCode' => 419,
                'title' => 'Miau... sua sessão perdeu uma vida.',
                'message' =>
                    'A sessão expirou ou o token de segurança '
                    . 'não é mais válido. Tente novamente.',
                'terminalCommand' => 'gatovel check session',
                'terminalMessage' => 'csrf token expired',
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