<?php

namespace nucleo\routing;

use nucleo\exceptions\routing\InvalidRouteDefinitionException;

class Route
{
    private static array $routes = [];

    public static function get(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = [],
        ?array $authorization = null
    ): void {
        self::register(
            'GET',
            $uri,
            $controller,
            $method,
            $middlewares,
            $authorization
        );
    }

    public static function post(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = [],
        ?array $authorization = null
    ): void {
        self::register(
            'POST',
            $uri,
            $controller,
            $method,
            $middlewares,
            $authorization
        );
    }

    public static function put(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = [],
        ?array $authorization = null
    ): void {
        self::register(
            'PUT',
            $uri,
            $controller,
            $method,
            $middlewares,
            $authorization
        );
    }

    public static function patch(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = [],
        ?array $authorization = null
    ): void {
        self::register(
            'PATCH',
            $uri,
            $controller,
            $method,
            $middlewares,
            $authorization
        );
    }

    public static function delete(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = [],
        ?array $authorization = null
    ): void {
        self::register(
            'DELETE',
            $uri,
            $controller,
            $method,
            $middlewares,
            $authorization
        );
    }

    public static function routes(): array
    {
        return self::$routes;
    }

    private static function register(
        string $httpMethod,
        string $uri,
        string $controller,
        string $method,
        array $middlewares,
        ?array $authorization
    ): void {
        self::validateUri(
            $uri
        );

        self::validateController(
            $controller
        );

        self::validateMethod(
            $method
        );

        self::validateMiddlewares(
            $middlewares
        );

        self::validateDuplicate(
            $httpMethod,
            $uri
        );

        self::$routes[$httpMethod][$uri] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares,
            'authorization' => $authorization,
        ];
    }

    private static function validateUri(
        string $uri
    ): void {
        if (
            $uri === ''
            || !str_starts_with(
                $uri,
                '/'
            )
        ) {
            throw new InvalidRouteDefinitionException(
                "URI inválida na definição da rota: {$uri}"
            );
        }
    }

    private static function validateController(
        string $controller
    ): void {
        if ($controller === '') {
            throw new InvalidRouteDefinitionException(
                'Controller inválido na definição da rota.'
            );
        }
    }

    private static function validateMethod(
        string $method
    ): void {
        if (
            !preg_match(
                '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
                $method
            )
        ) {
            throw new InvalidRouteDefinitionException(
                "Método inválido na definição da rota: {$method}"
            );
        }
    }

    private static function validateMiddlewares(
        array $middlewares
    ): void {
        foreach ($middlewares as $middleware) {

            if (
                !is_string($middleware)
                || $middleware === ''
            ) {
                throw new InvalidRouteDefinitionException(
                    'Middleware inválido na definição da rota.'
                );
            }
        }
    }

    private static function validateDuplicate(
        string $httpMethod,
        string $uri
    ): void {
        if (
            isset(
                self::$routes[$httpMethod][$uri]
            )
        ) {
            throw new InvalidRouteDefinitionException(
                "Rota duplicada: {$httpMethod} {$uri}"
            );
        }
    }
}