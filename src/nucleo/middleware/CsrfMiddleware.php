<?php

namespace nucleo\middleware;

use nucleo\exceptions\http\CsrfTokenMismatchException;
use nucleo\http\Request;
use nucleo\http\Response;
use nucleo\security\Csrf;

class CsrfMiddleware extends Middleware
{
    private const SAFE_METHODS = [
        'GET',
        'HEAD',
        'OPTIONS',
    ];

    public function handle(
        Request $request,
        callable $next
    ): Response {
        if (
            in_array(
                $request->method(),
                self::SAFE_METHODS,
                true
            )
        ) {
            return $next(
                $request
            );
        }

        $token = $this->tokenFromRequest(
            $request
        );

        if (!Csrf::validate($token)) {
            throw new CsrfTokenMismatchException();
        }

        return $next(
            $request
        );
    }

    private function tokenFromRequest(
        Request $request
    ): mixed {
        $headerToken = $request->header(
            Csrf::headerName()
        );

        if (
            is_string($headerToken)
            && $headerToken !== ''
        ) {
            return $headerToken;
        }

        return $request->post(
            Csrf::inputKey()
        );
    }
}
