<?php

namespace app\middlewares;

use nucleo\middleware\Middleware;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class AuthMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {

        if (!isset($_SESSION['user'])) {
            return Response::json(
                [
                    'error' => 'Não autenticado.'
                ],
                401
            );
        }

        return $next($request);
    }
}