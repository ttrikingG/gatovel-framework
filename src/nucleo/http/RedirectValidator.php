<?php

namespace nucleo\http;

use InvalidArgumentException;

class RedirectValidator
{
    private const ALLOWED_STATUSES = [
        301,
        302,
        303,
        307,
        308,
    ];

    public static function internal(
        string $url,
        int $status = 302
    ): string {
        self::validateStatus(
            $status
        );

        self::validateUrl(
            $url
        );

        if (!self::isInternal($url)) {
            throw new InvalidArgumentException(
                'O redirecionamento interno não pode apontar para uma URL externa.'
            );
        }

        return $url;
    }

    public static function external(
        string $url,
        int $status = 302
    ): string {
        self::validateStatus(
            $status
        );

        self::validateUrl(
            $url
        );

        $parts = parse_url(
            $url
        );

        if (
            $parts === false
            || !isset(
                $parts['scheme'],
                $parts['host']
            )
            || !in_array(
                strtolower($parts['scheme']),
                [
                    'http',
                    'https',
                ],
                true
            )
        ) {
            throw new InvalidArgumentException(
                'A URL externa de redirecionamento deve utilizar HTTP ou HTTPS.'
            );
        }

        return $url;
    }

    public static function referer(
        ?string $referer,
        string $fallback = '/',
        int $status = 302
    ): string {
        $fallback = self::internal(
            $fallback,
            $status
        );

        if (
            !is_string($referer)
            || $referer === ''
        ) {
            return $fallback;
        }

        try {
            self::validateUrl(
                $referer
            );
        } catch (InvalidArgumentException) {
            return $fallback;
        }

        if (!self::isSameOrigin($referer)) {
            return $fallback;
        }

        return $referer;
    }

    public static function validateStatus(
        int $status
    ): void {
        if (
            !in_array(
                $status,
                self::ALLOWED_STATUSES,
                true
            )
        ) {
            throw new InvalidArgumentException(
                "Status HTTP de redirecionamento inválido: {$status}"
            );
        }
    }

    private static function validateUrl(
        string $url
    ): void {
        if (trim($url) === '') {
            throw new InvalidArgumentException(
                'A URL de redirecionamento não pode ser vazia.'
            );
        }

        if (
            str_contains(
                $url,
                "\r"
            )
            || str_contains(
                $url,
                "\n"
            )
            || str_contains(
                $url,
                "\0"
            )
        ) {
            throw new InvalidArgumentException(
                'A URL de redirecionamento contém caracteres inválidos.'
            );
        }
    }

    private static function isInternal(
        string $url
    ): bool {
        if (
            str_starts_with(
                $url,
                '//'
            )
            || str_starts_with(
                $url,
                '\\\\'
            )
        ) {
            return false;
        }

        $parts = parse_url(
            $url
        );

        if ($parts === false) {
            return false;
        }

        if (
            isset($parts['scheme'])
            || isset($parts['host'])
        ) {
            return false;
        }

        return str_starts_with(
            $url,
            '/'
        );
    }

    private static function isSameOrigin(
        string $url
    ): bool {
        $parts = parse_url(
            $url
        );

        if (
            $parts === false
            || !isset($parts['host'])
        ) {
            return self::isInternal(
                $url
            );
        }

        $scheme = strtolower(
            (string) ($parts['scheme'] ?? '')
        );

        if (
            !in_array(
                $scheme,
                [
                    'http',
                    'https',
                ],
                true
            )
        ) {
            return false;
        }

        $requestHost = $_SERVER['HTTP_HOST']
            ?? '';

        if (!is_string($requestHost)) {
            return false;
        }

        $requestHost = strtolower(
            trim(
                $requestHost
            )
        );

        if ($requestHost === '') {
            return false;
        }

        $refererHost = strtolower(
            $parts['host']
        );

        $refererPort = $parts['port']
            ?? null;

        $refererAuthority = $refererHost;

        if ($refererPort !== null) {
            $refererAuthority .= ':'
                . $refererPort;
        }

        return hash_equals(
            $requestHost,
            $refererAuthority
        );
    }
}
