<?php

namespace nucleo\loadSupport;

class Route
{
    private static array $routes = [];

    public static function get(
        string $uri,
        string $controller,
        string $method = 'index'
    ): void {
        self::add('GET', $uri, $controller, $method);
    }

    public static function post(
        string $uri,
        string $controller,
        string $method = 'index'
    ): void {
        self::add('POST', $uri, $controller, $method);
    }

    public static function put(
        string $uri,
        string $controller,
        string $method = 'index'
    ): void {
        self::add('PUT', $uri, $controller, $method);
    }

    public static function patch(
        string $uri,
        string $controller,
        string $method = 'index'
    ): void {
        self::add('PATCH', $uri, $controller, $method);
    }

    public static function delete(
        string $uri,
        string $controller,
        string $method = 'index'
    ): void {
        self::add('DELETE', $uri, $controller, $method);
    }

    private static function add(
        string $httpMethod,
        string $uri,
        string $controller,
        string $method
    ): void {
        self::validateUri($uri);
        self::validateController($controller);
        self::validateMethod($method);

        if (isset(self::$routes[$httpMethod][$uri])) {
            throw new \Exception(
                "A rota {$httpMethod} {$uri} já foi registrada."
            );
        }

        self::$routes[$httpMethod][$uri] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    private static function validateUri(string $uri): void
    {
        if ($uri === '') {
            throw new \Exception(
                'A URI da rota não pode estar vazia.'
            );
        }

        if ($uri[0] !== '/') {
            throw new \Exception(
                'A URI da rota deve começar com /.'
            );
        }

        if (str_contains($uri, '//')) {
            throw new \Exception(
                'A URI da rota contém barras duplicadas.'
            );
        }

        preg_match_all(
            '/\{([^}]+)\}/',
            $uri,
            $matches
        );

        $parameters = $matches[1];

        if (count($parameters) !== count(array_unique($parameters))) {
            throw new \Exception(
                'A rota possui parâmetros duplicados.'
            );
        }

        foreach ($parameters as $parameter) {

            if (
                !preg_match(
                    '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
                    $parameter
                )
            ) {
                throw new \Exception(
                    "Parâmetro {$parameter} inválido."
                );
            }
        }
    }

    private static function validateController(
        string $controller
    ): void {
        if (
            !str_starts_with(
                $controller,
                'app\\controllers\\'
            )
        ) {
            throw new \Exception(
                'Controller inválido.'
            );
        }

        if (!class_exists($controller)) {
            throw new \Exception(
                "A classe {$controller} não existe."
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
            throw new \Exception(
                "Método {$method} inválido."
            );
        }
    }

    public static function routes(): array
    {
        return self::$routes;
    }
}