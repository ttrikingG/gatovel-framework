<?php

namespace nucleo\http;

use InvalidArgumentException;
use nucleo\session\Session;

class Redirect extends Response
{
    private const ERRORS_KEY = '_gatovel_errors';

    private function __construct(
        string $url,
        int $status = 302
    ) {
        parent::__construct(
            '',
            $status,
            [
                'Location' => $url,
            ]
        );
    }

    public static function to(
        string $url,
        int $status = 302
    ): static {
        if ($url === '') {
            throw new InvalidArgumentException(
                'A URL de redirecionamento não pode ser vazia.'
            );
        }

        return new static(
            $url,
            $status
        );
    }

    public static function back(
        string $fallback = '/',
        int $status = 302
    ): static {
        $referer = $_SERVER['HTTP_REFERER']
            ?? null;

        $url = is_string($referer)
            && $referer !== ''
                ? $referer
                : $fallback;

        return static::to(
            $url,
            $status
        );
    }

    public function with(
        string $key,
        mixed $value
    ): static {
        Session::flash(
            $key,
            $value
        );

        return $this;
    }

    public function withErrors(
        array $errors
    ): static {
        Session::flash(
            self::ERRORS_KEY,
            $errors
        );

        return $this;
    }

    public function withInput(
        array $input
    ): static {
        Session::flashInput(
            $input
        );

        return $this;
    }

    public static function errors(): array
    {
        $errors = Session::getFlash(
            self::ERRORS_KEY,
            []
        );

        return is_array($errors)
            ? $errors
            : [];
    }

    public static function hasErrors(
        ?string $field = null
    ): bool {
        $errors = static::errors();

        if ($field === null) {
            return $errors !== [];
        }

        return array_key_exists(
            $field,
            $errors
        );
    }

    public static function firstError(
        ?string $field = null
    ): ?string {
        $errors = static::errors();

        if ($field !== null) {
            $fieldErrors = $errors[$field]
                ?? [];

            if (!is_array($fieldErrors)) {
                return null;
            }

            $first = $fieldErrors[0]
                ?? null;

            return is_string($first)
                ? $first
                : null;
        }

        foreach ($errors as $fieldErrors) {
            if (!is_array($fieldErrors)) {
                continue;
            }

            $first = $fieldErrors[0]
                ?? null;

            if (is_string($first)) {
                return $first;
            }
        }

        return null;
    }
}
