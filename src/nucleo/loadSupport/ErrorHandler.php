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
            default => 500
        };

        Response::json(
            [
                'error' => match ($status) {
                    404 => 'Not Found.',
                    405 => 'Method Not Allowed.',
                    default => 'Internal Server Error.'
                }
            ],
            $status
        )->send();
    }
}