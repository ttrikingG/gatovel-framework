<?php

namespace app\middlewares;

use nucleo\middleware\Middleware;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class TestMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {

        $response = $next($request);

        return $response;
    }
}