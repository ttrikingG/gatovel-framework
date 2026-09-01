<?php

namespace app\middlewares;

use nucleo\middleware\Middleware;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class GuestMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {

        if (isset($_SESSION['user'])) {
            return Response::redirect('/home');
        }

        return $next($request);
    }
}