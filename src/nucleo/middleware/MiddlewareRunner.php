<?php

namespace nucleo\middleware;

use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class MiddlewareRunner
{
    public static function run(
        Request $request,
        array $middlewares,
        callable $controller
    ): Response {

        $next = function (
            Request $request
        ) use ($controller): Response {

            return $controller($request);
        };

        foreach (array_reverse($middlewares) as $middleware) {

            $next = function (
                Request $request
            ) use (
                $middleware,
                $next
            ): Response {

                $instance = new $middleware();

                return $instance->handle(
                    $request,
                    $next
                );
            };
        }

        return $next($request);
    }
}