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

            Response::json(
                $data,
                $statusCode
            )->send();

            return;
        }

        if ($debug) {
            $html = ErrorPageRenderer::renderDebug(
                $exception,
                $statusCode
            );

            Response::html(
                $html,
                $statusCode
            )->send();

            return;
        }

        $html = ErrorPageRenderer::render(
            $exception,
            $statusCode
        );

        Response::html(
            $html,
            $statusCode
        )->send();
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
