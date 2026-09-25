<?php

namespace nucleo\middleware;

use nucleo\auth\protection\Csrf;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class CsrfMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {

        if (
            in_array(
                $request->method(),
                ['POST', 'PUT', 'PATCH', 'DELETE'],
                true
            )
        ) {
            $token = $request->post(
                '_token',
                ''
            );

            if (
                !is_string($token)
                || !Csrf::validate($token)
            ) {
                return Response::json(
                    [
                        'error' => 'Token CSRF inválido.'
                    ],
                    403
                );
            }
        }

        return $next($request);
    }
}
