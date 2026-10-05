<?php

namespace nucleo\errors;

use nucleo\exceptions\GatovelException;
use nucleo\exceptions\http\ValidationException;
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
            $error = [
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'status' => $statusCode,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];

            if ($exception instanceof ValidationException) {
                $error['errors'] = $exception->errors();
            }

            return [
                'error' => $error,
            ];
        }

        $error = [
            'message' => self::safeMessage(
                $statusCode
            ),
            'status' => $statusCode,
        ];

        if ($exception instanceof ValidationException) {
            $error['message'] = $exception->getMessage();
            $error['errors'] = $exception->errors();
        }

        return [
            'error' => $error,
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
            400 => 'Bad Request.',
            401 => 'Unauthorized.',
            403 => 'Forbidden.',
            404 => 'Not Found.',
            405 => 'Method Not Allowed.',
            409 => 'Conflict.',
            419 => 'CSRF Token Mismatch.',
            422 => 'Unprocessable Entity.',
            429 => 'Too Many Requests.',
            default => 'Internal Server Error.',
        };
    }
}
