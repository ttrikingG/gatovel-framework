<?php

namespace nucleo\loadSupport;

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
        self::$routes['GET'][$uri] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares,
            'authorization' => $authorization
        ];
    }

    public static function post(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = [],
        ?array $authorization = null
    ): void {
        self::$routes['POST'][$uri] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares,
            'authorization' => $authorization
        ];
    }

    public static function put(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = [],
        ?array $authorization = null
    ): void {
        self::$routes['PUT'][$uri] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares,
            'authorization' => $authorization
        ];
    }

    public static function delete(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = [],
        ?array $authorization = null
    ): void {
        self::$routes['DELETE'][$uri] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares,
            'authorization' => $authorization
        ];
    }

    public static function routes(): array
    {
        return self::$routes;
    }
}