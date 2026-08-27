<?php

namespace nucleo\loadSupport;

class Router
{
    private static array $parameters = [];

    private static string $method = 'index';

    private static array $currentRoute = [];

    public static function resolve(
        Request $request
    ): string {

        self::$parameters = [];
        self::$method = 'index';
        self::$currentRoute = [];

        $uri = $request->uri();
        $requestMethod = $request->method();

        $routes = Route::routes();

        foreach (
            $routes[$requestMethod] ?? []
            as $route => $data
        ) {
            if (self::match($route, $uri)) {

                self::$method = $data['method'];

                self::$currentRoute = $data;

                return $data['controller'];
            }
        }

        foreach ($routes as $registeredMethod => $methodRoutes) {

            if ($registeredMethod === $requestMethod) {
                continue;
            }

            foreach ($methodRoutes as $route => $data) {

                if (self::match($route, $uri)) {

                    throw new \Exception(
                        'Method Not Allowed.',
                        405
                    );
                }
            }
        }

        throw new \Exception(
            'Not Found.',
            404
        );
    }

    private static function match(
        string $route,
        string $uri
    ): bool {
        $parameterNames = [];

        $pattern = preg_quote($route, '#');

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

        $pattern = '#^' . $pattern . '$#';

        $matches = [];

        if (
            preg_match(
                $pattern,
                $uri,
                $matches
            ) !== 1
        ) {
            return false;
        }

        array_shift($matches);

        self::$parameters = array_combine(
            $parameterNames,
            $matches
        ) ?: [];

        return true;
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
}

