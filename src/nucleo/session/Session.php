<?php

namespace nucleo\session;

class Session
{
    public static function start(): void
    {
        SessionManager::start();
    }

    public static function isStarted(): bool
    {
        return SessionManager::isStarted();
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

        Flash::markPrepared();
    }

    public static function regenerate(
        bool $deleteOldSession = true
    ): void {
        SessionManager::regenerate(
            $deleteOldSession
        );
    }

    public static function close(): void
    {
        SessionManager::close();
    }

    public static function destroy(): void
    {
        SessionManager::destroy();
    }

    public static function flash(
        string $key,
        mixed $value
    ): void {
        self::start();

        Flash::put(
            $key,
            $value
        );
    }

    public static function getFlash(
        string $key,
        mixed $default = null
    ): mixed {
        self::start();

        return Flash::get(
            $key,
            $default
        );
    }

    public static function hasFlash(
        string $key
    ): bool {
        self::start();

        return Flash::has(
            $key
        );
    }

    public static function flashAll(): array
    {
        self::start();

        return Flash::all();
    }

    public static function flashInput(
        array $input
    ): void {
        self::start();

        Flash::input(
            $input
        );
    }

    public static function old(
        string $key,
        mixed $default = null
    ): mixed {
        self::start();

        return Flash::old(
            $key,
            $default
        );
    }

    public static function hasOld(
        string $key
    ): bool {
        self::start();

        return Flash::hasOld(
            $key
        );
    }

    public static function oldInput(): array
    {
        self::start();

        return Flash::oldInput();
    }
}
