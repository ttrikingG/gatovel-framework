<?php

namespace nucleo\loadSupport;

class Request
{
    private ?array $body = null;

    private ?array $uploadedFiles = null;

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
        return $this->body()[$key]
            ?? $default;
    }

    public function input(
        string $key,
        mixed $default = null
    ): mixed {
        $body = $this->body();

        if (
            array_key_exists(
                $key,
                $body
            )
        ) {
            return $body[$key];
        }

        return $_GET[$key]
            ?? $default;
    }

    public function has(
        string $key
    ): bool {
        $body = $this->body();

        return array_key_exists(
            $key,
            $body
        )
            || array_key_exists(
                $key,
                $_GET
            );
    }

    public function only(
        array $keys
    ): array {
        $data = [];

        foreach ($keys as $key) {
            if (!is_string($key)) {
                continue;
            }

            if ($this->has($key)) {
                $data[$key] = $this->input(
                    $key
                );
            }
        }

        return $data;
    }

    public function file(
        string $key
    ): UploadedFile|array|null {
        $files = $this->files();

        return $files[$key]
            ?? null;
    }

    public function hasFile(
        string $key
    ): bool {
        $file = $this->file(
            $key
        );

        if ($file instanceof UploadedFile) {
            return $file->error()
                !== UPLOAD_ERR_NO_FILE;
        }

        if (is_array($file)) {
            return $this->containsUploadedFile(
                $file
            );
        }

        return false;
    }

    public function files(): array
    {
        if ($this->uploadedFiles !== null) {
            return $this->uploadedFiles;
        }

        $this->uploadedFiles = [];

        foreach ($_FILES as $key => $file) {
            if (
                !is_string($key)
                || !is_array($file)
            ) {
                continue;
            }

            $normalized = $this->normalizeUploadedFile(
                $file
            );

            if ($normalized !== null) {
                $this->uploadedFiles[$key]
                    = $normalized;
            }
        }

        return $this->uploadedFiles;
    }

    public function header(
        string $name,
        mixed $default = null
    ): mixed {
        $normalizedName = strtoupper(
            str_replace(
                '-',
                '_',
                $name
            )
        );

        if (
            in_array(
                $normalizedName,
                [
                    'CONTENT_TYPE',
                    'CONTENT_LENGTH',
                ],
                true
            )
        ) {
            return $_SERVER[$normalizedName]
                ?? $default;
        }

        $key = 'HTTP_'
            . $normalizedName;

        return $_SERVER[$key]
            ?? $default;
    }

    public function contentType(): ?string
    {
        $contentType = $this->header(
            'Content-Type'
        );

        if (
            !is_string($contentType)
            || $contentType === ''
        ) {
            return null;
        }

        $parts = explode(
            ';',
            $contentType,
            2
        );

        return strtolower(
            trim($parts[0])
        );
    }

    public function expectsJson(): bool
    {
        $accept = $this->header(
            'Accept',
            ''
        );

        if (
            !is_string($accept)
            || $accept === ''
        ) {
            return false;
        }

        $accept = strtolower(
            $accept
        );

        return str_contains(
            $accept,
            'application/json'
        )
            || str_contains(
                $accept,
                '+json'
            );
    }

    public function cookie(
        string $key,
        mixed $default = null
    ): mixed {
        return $_COOKIE[$key]
            ?? $default;
    }

    public function ip(): ?string
    {
        $ip = $_SERVER['REMOTE_ADDR']
            ?? null;

        if (
            !is_string($ip)
            || $ip === ''
        ) {
            return null;
        }

        return $ip;
    }

    public function all(): array
    {
        return [
            'method' => $this->method(),
            'uri' => $this->uri(),
            'query' => $_GET,
            'body' => $this->body(),
            'files' => $this->files(),
        ];
    }

    private function body(): array
    {
        if ($this->body !== null) {
            return $this->body;
        }

        $method = $this->method();

        if (
            !in_array(
                $method,
                [
                    'POST',
                    'PUT',
                    'PATCH',
                    'DELETE',
                ],
                true
            )
        ) {
            $this->body = [];

            return $this->body;
        }

        if (
            $this->contentType()
            === 'application/json'
        ) {
            return $this->jsonBody();
        }

        if ($method === 'POST') {
            $this->body = $_POST;

            return $this->body;
        }

        $content = $this->rawBody();

        if ($content === '') {
            $this->body = [];

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

    private function jsonBody(): array
    {
        $content = $this->rawBody();

        if ($content === '') {
            $this->body = [];

            return $this->body;
        }

        $data = json_decode(
            $content,
            true
        );

        $this->body = is_array($data)
            ? $data
            : [];

        return $this->body;
    }

    private function rawBody(): string
    {
        $content = file_get_contents(
            'php://input'
        );

        if (!is_string($content)) {
            return '';
        }

        return trim($content);
    }

    private function normalizeUploadedFile(
        array $file
    ): UploadedFile|array|null {
        if (
            !array_key_exists('name', $file)
            || !array_key_exists('tmp_name', $file)
            || !array_key_exists('error', $file)
            || !array_key_exists('size', $file)
        ) {
            return null;
        }

        if (is_array($file['name'])) {
            return $this->normalizeUploadedFileArray(
                $file['name'],
                $file['tmp_name'],
                $file['error'],
                $file['size']
            );
        }

        if (
            !is_string($file['name'])
            || !is_string($file['tmp_name'])
            || !is_int($file['error'])
            || !is_int($file['size'])
        ) {
            return null;
        }

        return new UploadedFile(
            $file['name'],
            $file['tmp_name'],
            $file['error'],
            $file['size']
        );
    }

    private function normalizeUploadedFileArray(
        array $names,
        mixed $temporaryPaths,
        mixed $errors,
        mixed $sizes
    ): array {
        if (
            !is_array($temporaryPaths)
            || !is_array($errors)
            || !is_array($sizes)
        ) {
            return [];
        }

        $files = [];

        foreach ($names as $key => $name) {
            if (
                !array_key_exists($key, $temporaryPaths)
                || !array_key_exists($key, $errors)
                || !array_key_exists($key, $sizes)
            ) {
                continue;
            }

            if (is_array($name)) {
                $files[$key]
                    = $this->normalizeUploadedFileArray(
                        $name,
                        $temporaryPaths[$key],
                        $errors[$key],
                        $sizes[$key]
                    );

                continue;
            }

            if (
                !is_string($name)
                || !is_string($temporaryPaths[$key])
                || !is_int($errors[$key])
                || !is_int($sizes[$key])
            ) {
                continue;
            }

            $files[$key] = new UploadedFile(
                $name,
                $temporaryPaths[$key],
                $errors[$key],
                $sizes[$key]
            );
        }

        return $files;
    }

    private function containsUploadedFile(
        array $files
    ): bool {
        foreach ($files as $file) {
            if (
                $file instanceof UploadedFile
                && $file->error()
                    !== UPLOAD_ERR_NO_FILE
            ) {
                return true;
            }

            if (
                is_array($file)
                && $this->containsUploadedFile(
                    $file
                )
            ) {
                return true;
            }
        }

        return false;
    }
}
