<?php

namespace nucleo\errors;

use nucleo\exceptions\GatovelException;
use Throwable;

class ErrorRenderer
{
    public static function render(
        Throwable $exception,
        bool $debug = false
    ): array {
        $statusCode = self::statusCode(
            $exception
        );

        if ($debug) {
            return [
                'error' => [
                    'type' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'status' => $statusCode,
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                ],
            ];
        }

        return [
            'error' => [
                'message' => self::safeMessage(
                    $statusCode
                ),
                'status' => $statusCode,
            ],
        ];
    }

    private static function statusCode(
        Throwable $exception
    ): int {
        if ($exception instanceof GatovelException) {
            return $exception->statusCode();
        }

        return 500;
    }

    private static function safeMessage(
        int $statusCode
    ): string {
        return match ($statusCode) {
            404 => 'Not Found.',
            405 => 'Method Not Allowed.',
            419 => 'Token CSRF inválido.',
            default => 'Internal Server Error.',
        };
    }
}
