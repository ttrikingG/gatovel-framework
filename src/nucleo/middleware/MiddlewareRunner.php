<?php

namespace nucleo\middleware;

use nucleo\exceptions\InvalidRouteDefinitionException;
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
            return $controller(
                $request
            );
        };

        foreach (
            array_reverse($middlewares)
            as $middleware
        ) {
            self::validateMiddleware(
                $middleware
            );

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

        return $next(
            $request
        );
    }

    private static function validateMiddleware(
        mixed $middleware
    ): void {
        if (
            !is_string($middleware)
            || $middleware === ''
        ) {
            throw new InvalidRouteDefinitionException(
                'Middleware inválido na definição da rota.'
            );
        }

        if (!class_exists($middleware)) {
            throw new InvalidRouteDefinitionException(
                "Middleware não encontrado: {$middleware}"
            );
        }

        if (
            !is_subclass_of(
                $middleware,
                Middleware::class
            )
        ) {
            throw new InvalidRouteDefinitionException(
                "O middleware {$middleware} deve estender "
                . Middleware::class
                . '.'
            );
        }
    }
}