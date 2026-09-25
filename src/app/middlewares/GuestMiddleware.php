<?php

namespace app\middlewares;

use nucleo\auth\authentication\Auth;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;
use nucleo\middleware\Middleware;

class GuestMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {

        if (Auth::check()) {
            return Response::redirect('/home');
        }

        return $next($request);
    }
}