<?php

namespace nucleo\loadSupport;

class Request
{
    public function method(): string
    {
        return strtoupper(
            $_SERVER['REQUEST_METHOD'] ?? 'GET'
        );
    }

    public function uri(): string
    {
        return Uri::uri();
    }

    public function query(
        ?string $key = null,
        mixed $default = null
    ): mixed {
        if ($key === null) {
            return $_GET;
        }

        return $_GET[$key] ?? $default;
    }

    public function post(
        ?string $key = null,
        mixed $default = null
    ): mixed {
        if ($key === null) {
            return $_POST;
        }

        return $_POST[$key] ?? $default;
    }

    public function input(
        ?string $key = null,
        mixed $default = null
    ): mixed {
        if ($key === null) {
            return array_merge($_GET, $_POST);
        }

        return $_POST[$key]
            ?? $_GET[$key]
            ?? $default;
    }

    public function header(
        string $name,
        mixed $default = null
    ): mixed {
        $serverKey = 'HTTP_' . strtoupper(
            str_replace('-', '_', $name)
        );

        return $_SERVER[$serverKey] ?? $default;
    }

    public function all(): array
    {
        return [
            'method' => $this->method(),
            'uri' => $this->uri(),
            'query' => $_GET,
            'post' => $_POST,
        ];
    }
}
