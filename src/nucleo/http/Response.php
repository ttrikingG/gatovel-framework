<?php

namespace nucleo\http;

use InvalidArgumentException;

class Response
{
    private int $status;

    private array $headers = [];

    private string $content;

    public function __construct(
        string $content = '',
        int $status = 200,
        array $headers = []
    ) {
        $this->validateStatus(
            $status
        );

        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function send(): void
    {
        http_response_code(
            $this->status
        );

        foreach (
            $this->headers as $name => $value
        ) {
            header(
                "{$name}: {$value}"
            );
        }

        echo $this->content;
    }

    public function status(
        int $status
    ): static {
        $this->validateStatus(
            $status
        );

        $this->status = $status;

        return $this;
    }

    public function statusCode(): int
    {
        return $this->status;
    }

    public function header(
        string $name,
        string $value
    ): static {
        $this->headers[$name] = $value;

        return $this;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function content(
        string $content
    ): static {
        $this->content = $content;

        return $this;
    }

    public function body(): string
    {
        return $this->content;
    }

    public static function html(
        string $content,
        int $status = 200
    ): static {
        return new static(
            $content,
            $status,
            [
                'Content-Type'
                    => 'text/html; charset=UTF-8',
            ]
        );
    }

    public static function json(
        mixed $data,
        int $status = 200
    ): static {
        $content = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES |
            JSON_THROW_ON_ERROR
        );

        return new static(
            $content,
            $status,
            [
                'Content-Type'
                    => 'application/json; charset=UTF-8',
            ]
        );
    }

    public static function text(
        string $content,
        int $status = 200
    ): static {
        return new static(
            $content,
            $status,
            [
                'Content-Type'
                    => 'text/plain; charset=UTF-8',
            ]
        );
    }

    public static function noContent(): static
    {
        return new static(
            '',
            204
        );
    }

    public static function redirect(
        string $url,
        int $status = 302
    ): static {
        $url = RedirectValidator::internal(
            $url,
            $status
        );

        return new static(
            '',
            $status,
            [
                'Location' => $url,
            ]
        );
    }

    private function validateStatus(
        int $status
    ): void {
        if (
            $status < 100
            || $status > 599
        ) {
            throw new InvalidArgumentException(
                "Status HTTP inválido: {$status}"
            );
        }
    }
}
