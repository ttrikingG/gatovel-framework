<?php

namespace nucleo\session;

class Flash
{
    private const FLASH_KEY = '_gatovel_flash';

    private const OLD_INPUT_KEY = '_gatovel_old_input';

    private static bool $prepared = false;

    public static function put(
        string $key,
        mixed $value
    ): void {
        $_SESSION[self::FLASH_KEY]['new'][$key]
            = $value;
    }

    public static function get(
        string $key,
        mixed $default = null
    ): mixed {
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

    public static function has(
        string $key
    ): bool {
        return array_key_exists(
            $key,
            $_SESSION[self::FLASH_KEY]['old']
                ?? []
        );
    }

    public static function all(): array
    {
        return $_SESSION[self::FLASH_KEY]['old']
            ?? [];
    }

    public static function input(
        array $input
    ): void {
        self::put(
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
        $input = self::get(
            self::OLD_INPUT_KEY,
            []
        );

        return is_array($input)
            ? $input
            : [];
    }

    public static function prepare(): void
    {
        if (self::$prepared) {
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

        self::$prepared = true;
    }

    public static function reset(): void
    {
        self::$prepared = false;
    }

    public static function markPrepared(): void
    {
        self::$prepared = true;
    }
}
