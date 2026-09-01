<?php

namespace nucleo\loadSupport;

class Response
{
    private int $status;

    private array $headers = [];

    private mixed $content;

    public function __construct(
        mixed $content = '',
        int $status = 200,
        array $headers = []
    ) {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->content;
    }

    public function status(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function header(
        string $name,
        string $value
    ): static {
        $this->headers[$name] = $value;

        return $this;
    }

    public function content(mixed $content): static
    {
        $this->content = $content;

        return $this;
    }

    public static function html(
        string $content,
        int $status = 200
    ): static {
        return new static(
            $content,
            $status,
            [
                'Content-Type' => 'text/html; charset=UTF-8'
            ]
        );
    }

    public static function json(
        mixed $data,
        int $status = 200
    ): static {
        return new static(
            json_encode(
                $data,
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES |
                JSON_THROW_ON_ERROR
            ),
            $status,
            [
                'Content-Type' => 'application/json; charset=UTF-8'
            ]
        );
    }

    public static function redirect(
        string $url,
        int $status = 302
    ): static {
        return new static(
            '',
            $status,
            [
                'Location' => $url
            ]
        );
    }
}