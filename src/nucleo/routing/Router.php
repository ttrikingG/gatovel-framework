<?php

namespace nucleo\routing;

use nucleo\exceptions\http\MethodNotAllowedException;
use nucleo\exceptions\routing\RouteNotFoundException;
use nucleo\http\Request;

class Router
{
    private static array $parameters = [];

    private static string $method = 'index';

    private static array $currentRoute = [];

    public static function resolve(
        Request $request
    ): string {
        self::reset();

        $uri = $request->uri();
        $requestMethod = $request->method();

        $routes = Route::routes();

        $matchedRoute = self::findRoute(
            $routes[$requestMethod] ?? [],
            $uri
        );

        if ($matchedRoute !== null) {
            self::$parameters = $matchedRoute['parameters'];

            self::$method = $matchedRoute['data']['method'];

            self::$currentRoute = $matchedRoute['data'];

            return $matchedRoute['data']['controller'];
        }

        foreach (
            $routes as $registeredMethod => $methodRoutes
        ) {
            if ($registeredMethod === $requestMethod) {
                continue;
            }

            if (
                self::findRoute(
                    $methodRoutes,
                    $uri
                ) !== null
            ) {
                throw new MethodNotAllowedException(
                    $requestMethod,
                    $uri
                );
            }
        }

        throw new RouteNotFoundException(
            $requestMethod,
            $uri
        );
    }

    public static function parameters(): array
    {
        return self::$parameters;
    }

    public static function method(): string
    {
        return self::$method;
    }

    public static function currentRoute(): array
    {
        return self::$currentRoute;
    }

    private static function findRoute(
        array $routes,
        string $uri
    ): ?array {
        foreach (
            self::orderedRoutes($routes)
            as $route => $data
        ) {
            $parameters = self::match(
                $route,
                $uri
            );

            if ($parameters === null) {
                continue;
            }

            return [
                'data' => $data,
                'parameters' => $parameters,
            ];
        }

        return null;
    }

    private static function orderedRoutes(
        array $routes
    ): array {
        $staticRoutes = [];
        $dynamicRoutes = [];

        foreach ($routes as $route => $data) {
            if (
                str_contains(
                    $route,
                    '{'
                )
            ) {
                $dynamicRoutes[$route] = $data;

                continue;
            }

            $staticRoutes[$route] = $data;
        }

        return $staticRoutes + $dynamicRoutes;
    }

    private static function match(
        string $route,
        string $uri
    ): ?array {
        $parameterNames = [];

        $pattern = preg_quote(
            $route,
            '#'
        );

        $pattern = preg_replace_callback(
            '/\\\\\{([^}]+)\\\\\}/',
            function ($match) use (&$parameterNames) {
                $name = $match[1];

                if (
                    !preg_match(
                        '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
                        $name
                    )
                ) {
                    return '(?!x)x';
                }

                $parameterNames[] = $name;

                return '([^/]+)';
            },
            $pattern
        );

        $pattern = '#^'
            . $pattern
            . '$#';

        $matches = [];

        if (
            preg_match(
                $pattern,
                $uri,
                $matches
            ) !== 1
        ) {
            return null;
        }

        array_shift(
            $matches
        );

        if (empty($parameterNames)) {
            return [];
        }

        return array_combine(
            $parameterNames,
            $matches
        ) ?: [];
    }

    private static function reset(): void
    {
        self::$parameters = [];
        self::$method = 'index';
        self::$currentRoute = [];
    }
}
