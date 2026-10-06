<?php

namespace nucleo\security;

use nucleo\session\Session;

class Csrf
{
    private const SESSION_KEY = '_gatovel_csrf_token';

    private const INPUT_KEY = '_token';

    private const HEADER_NAME = 'X-CSRF-TOKEN';

    public static function token(): string
    {
        $token = Session::get(
            self::SESSION_KEY
        );

        if (
            is_string($token)
            && $token !== ''
        ) {
            return $token;
        }

        $token = bin2hex(
            random_bytes(32)
        );

        Session::set(
            self::SESSION_KEY,
            $token
        );

        return $token;
    }

    public static function field(): string
    {
        $name = htmlspecialchars(
            self::INPUT_KEY,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        $token = htmlspecialchars(
            self::token(),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        return sprintf(
            '<input type="hidden" name="%s" value="%s">',
            $name,
            $token
        );
    }

    public static function validate(
        mixed $token
    ): bool {
        if (
            !is_string($token)
            || $token === ''
        ) {
            return false;
        }

        $storedToken = Session::get(
            self::SESSION_KEY
        );

        if (
            !is_string($storedToken)
            || $storedToken === ''
        ) {
            return false;
        }

        return hash_equals(
            $storedToken,
            $token
        );
    }

    public static function regenerate(): string
    {
        Session::remove(
            self::SESSION_KEY
        );

        return self::token();
    }

    public static function inputKey(): string
    {
        return self::INPUT_KEY;
    }

    public static function headerName(): string
    {
        return self::HEADER_NAME;
    }
}
