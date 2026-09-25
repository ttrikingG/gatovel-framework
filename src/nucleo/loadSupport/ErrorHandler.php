<?php

namespace nucleo\loadSupport;

class ErrorHandler
{
    public static function handle(
        \Throwable $exception
    ): void {
        $status = match ($exception->getCode()) {
            404 => 404,
            405 => 405,
            419 => 419,
            default => 500
        };

        Response::json(
            [
                'error' => match ($status) {
                    404 => 'Not Found.',
                    405 => 'Method Not Allowed.',
                    419 => 'Token CSRF inválido.',
                    default => 'Internal Server Error.'
                }
            ],
            $status
        )->send();
    }
}