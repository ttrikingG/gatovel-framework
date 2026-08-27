<?php

namespace nucleo\loadSupport;

class Route
{
    private static array $routes = [];

    public static function get(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = []
    ): void {
        self::$routes['GET'][$uri] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares
        ];
    }

    public static function post(
        string $uri,
        string $controller,
        string $method = 'index',
        array $middlewares = []
    ): void {
        self::$routes['POST'][$uri] = [
            'controller' => $controller,
            'method' => $method,
            'middlewares' => $middlewares
        ];
    }

    public static function routes(): array
    {
        return self::$routes;
    }
}