<?php

namespace nucleo\security;

use nucleo\http\Request;
use RuntimeException;

class Csp
{
    public const NONCE_ATTRIBUTE = 'csp_nonce';

    public static function nonce(
        Request $request
    ): string {
        $existing = $request->attribute(
            self::NONCE_ATTRIBUTE
        );

        if (
            is_string($existing)
            && $existing !== ''
        ) {
            return $existing;
        }

        $nonce = base64_encode(
            random_bytes(32)
        );

        $request->setAttribute(
            self::NONCE_ATTRIBUTE,
            $nonce
        );

        return $nonce;
    }

    public static function existingNonce(
        Request $request
    ): string {
        $nonce = $request->attribute(
            self::NONCE_ATTRIBUTE
        );

        if (
            !is_string($nonce)
            || $nonce === ''
        ) {
            throw new RuntimeException(
                'CSP nonce ainda não foi gerado para esta requisição.'
            );
        }

        return $nonce;
    }

    public static function policy(
        Request $request
    ): string {
        $nonce = self::nonce(
            $request
        );

        return implode(
            '; ',
            [
                "default-src 'self'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'",
                "object-src 'none'",
                "script-src 'self' 'nonce-{$nonce}'",
                "style-src 'self' 'nonce-{$nonce}'",
                "img-src 'self' data:",
                "font-src 'self'",
                "connect-src 'self'",
            ]
        );
    }

    public static function htmlNonce(
        Request $request
    ): string {
        return htmlspecialchars(
            self::nonce($request),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }
}
