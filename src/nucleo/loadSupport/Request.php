<?php

namespace nucleo\loadSupport;

class Request
{
    private ?array $body = null;

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
        string $key,
        mixed $default = null
    ): mixed {
        return $_GET[$key] ?? $default;
    }

    public function post(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->body()[$key] ?? $default;
    }

    public function input(
        string $key,
        mixed $default = null
    ): mixed {
        if (array_key_exists($key, $this->body())) {
            return $this->body()[$key];
        }

        return $_GET[$key] ?? $default;
    }

    public function header(
        string $name,
        mixed $default = null
    ): mixed {
        $key = 'HTTP_' . strtoupper(
            str_replace('-', '_', $name)
        );

        return $_SERVER[$key] ?? $default;
    }

    public function all(): array
    {
        return [
            'method' => $this->method(),
            'uri' => $this->uri(),
            'query' => $_GET,
            'post' => $this->body(),
        ];
    }

    private function body(): array
    {
        if ($this->body !== null) {
            return $this->body;
        }

        if ($this->method() === 'POST') {
            $this->body = $_POST;

            return $this->body;
        }

        if (
            !in_array(
                $this->method(),
                ['PUT', 'PATCH', 'DELETE'],
                true
            )
        ) {
            $this->body = [];

            return $this->body;
        }

        $content = file_get_contents(
            'php://input'
        );

        if (
            !is_string($content)
            || trim($content) === ''
        ) {
            $this->body = [];

            return $this->body;
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (
            str_contains(
                $contentType,
                'application/json'
            )
        ) {
            $data = json_decode(
                $content,
                true
            );

            $this->body = is_array($data)
                ? $data
                : [];

            return $this->body;
        }

        $data = [];

        parse_str(
            $content,
            $data
        );

        $this->body = $data;

        return $this->body;
    }
}