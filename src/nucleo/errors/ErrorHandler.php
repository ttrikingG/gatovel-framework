<?php

namespace nucleo\errors;

use nucleo\config\Config;
use nucleo\exceptions\GatovelException;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;
use nucleo\logging\Logger;
use Throwable;

class ErrorHandler
{
    public static function handle(
        Throwable $exception,
        ?Request $request = null
    ): void {
        self::render(
            $exception,
            $request
        )->send();
    }

    public static function render(
        Throwable $exception,
        ?Request $request = null
    ): Response {
        Logger::error(
            $exception
        );

        $statusCode = self::statusCode(
            $exception
        );

        $debug = (bool) Config::get(
            'app.debug',
            false
        );

        if (
            $request !== null
            && $request->expectsJson()
        ) {
            $data = ErrorRenderer::render(
                $exception,
                $debug
            );

            return Response::json(
                $data,
                $statusCode
            );
        }

        if ($debug) {
            $html = ErrorPageRenderer::renderDebug(
                $exception,
                $statusCode
            );

            return Response::html(
                $html,
                $statusCode
            );
        }

        $html = ErrorPageRenderer::render(
            $exception,
            $statusCode
        );

        return Response::html(
            $html,
            $statusCode
        );
    }

    private static function statusCode(
        Throwable $exception
    ): int {
        if ($exception instanceof GatovelException) {
            return $exception->statusCode();
        }

        return 500;
    }
}
