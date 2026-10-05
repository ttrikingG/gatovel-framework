<?php

namespace nucleo\session;

use RuntimeException;

class Session
{
    private const FLASH_KEY = '_gatovel_flash';

    private const OLD_INPUT_KEY = '_gatovel_old_input';

    private static bool $flashPrepared = false;

    public static function start(): void
    {
        if (!self::isStarted()) {
            if (headers_sent()) {
                throw new RuntimeException(
                    'Não foi possível iniciar a sessão porque os headers já foram enviados.'
                );
            }

            $started = session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);

            if (!$started) {
                throw new RuntimeException(
                    'Não foi possível iniciar a sessão.'
                );
            }

            self::$flashPrepared = false;
        }

        self::prepareFlash();
    }

    public static function isStarted(): bool
    {
        return session_status()
            === PHP_SESSION_ACTIVE;
    }

    public static function set(
        string $key,
        mixed $value
    ): void {
        self::start();

        $_SESSION[$key] = $value;
    }

    public static function get(
        string $key,
        mixed $default = null
    ): mixed {
        self::start();

        if (
            !array_key_exists(
                $key,
                $_SESSION
            )
        ) {
            return $default;
        }

        return $_SESSION[$key];
    }

    public static function has(
        string $key
    ): bool {
        self::start();

        return array_key_exists(
            $key,
            $_SESSION
        );
    }

    public static function remove(
        string $key
    ): void {
        self::start();

        unset(
            $_SESSION[$key]
        );
    }

    public static function all(): array
    {
        self::start();

        return $_SESSION;
    }

    public static function clear(): void
    {
        self::start();

        $_SESSION = [];

        self::$flashPrepared = true;
    }

    public static function regenerate(
        bool $deleteOldSession = true
    ): void {
        self::start();

        if (
            !session_regenerate_id(
                $deleteOldSession
            )
        ) {
            throw new RuntimeException(
                'Não foi possível regenerar o identificador da sessão.'
            );
        }
    }

    public static function close(): void
    {
        if (!self::isStarted()) {
            return;
        }

        session_write_close();

        self::$flashPrepared = false;
    }

    public static function destroy(): void
    {
        if (!self::isStarted()) {
            return;
        }

        $_SESSION = [];

        if (!session_destroy()) {
            throw new RuntimeException(
                'Não foi possível destruir a sessão.'
            );
        }

        self::$flashPrepared = false;
    }

    public static function flash(
        string $key,
        mixed $value
    ): void {
        self::start();

        $_SESSION[self::FLASH_KEY]['new'][$key]
            = $value;
    }

    public static function getFlash(
        string $key,
        mixed $default = null
    ): mixed {
        self::start();

        $flash = $_SESSION[self::FLASH_KEY]['old']
            ?? [];

        if (
            !array_key_exists(
                $key,
                $flash
            )
        ) {
            return $default;
        }

        return $flash[$key];
    }

    public static function hasFlash(
        string $key
    ): bool {
        self::start();

        return array_key_exists(
            $key,
            $_SESSION[self::FLASH_KEY]['old']
                ?? []
        );
    }

    public static function flashAll(): array
    {
        self::start();

        return $_SESSION[self::FLASH_KEY]['old']
            ?? [];
    }

    public static function flashInput(
        array $input
    ): void {
        self::flash(
            self::OLD_INPUT_KEY,
            $input
        );
    }

    public static function old(
        string $key,
        mixed $default = null
    ): mixed {
        $input = self::oldInput();

        if ($key === '') {
            return $default;
        }

        $segments = explode(
            '.',
            $key
        );

        $value = $input;

        foreach ($segments as $segment) {
            if (
                !is_array($value)
                || !array_key_exists(
                    $segment,
                    $value
                )
            ) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public static function hasOld(
        string $key
    ): bool {
        $marker = new \stdClass();

        return self::old(
            $key,
            $marker
        ) !== $marker;
    }

    public static function oldInput(): array
    {
        $input = self::getFlash(
            self::OLD_INPUT_KEY,
            []
        );

        return is_array($input)
            ? $input
            : [];
    }

    private static function prepareFlash(): void
    {
        if (self::$flashPrepared) {
            return;
        }

        $flash = $_SESSION[self::FLASH_KEY]
            ?? [];

        $new = is_array(
            $flash['new'] ?? null
        )
            ? $flash['new']
            : [];

        unset(
            $_SESSION[self::FLASH_KEY]
        );

        if ($new !== []) {
            $_SESSION[self::FLASH_KEY] = [
                'old' => $new,
                'new' => [],
            ];
        }

        self::$flashPrepared = true;
    }
}
