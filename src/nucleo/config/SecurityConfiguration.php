<?php

namespace nucleo\config;

use nucleo\exceptions\configuration\ConfigurationException;

class SecurityConfiguration
{
    /**
     * Valida configurações de segurança da aplicação.
     *
     * Uma configuração insegura interrompe o bootstrap
     * antes que a aplicação comece a atender requisições.
     */
    public static function validate(): void
    {
        self::validateSession();
    }

    /**
     * Valida as configurações relacionadas ao cookie
     * utilizado pela sessão.
     */
    private static function validateSession(): void
    {
        $environment = strtolower(
            trim(
                (string) Config::get(
                    'app.env',
                    'production'
                )
            )
        );

        $secure = (bool) Config::get(
            'session.secure',
            false
        );

        $httpOnly = (bool) Config::get(
            'session.http_only',
            true
        );

        $sameSite = strtolower(
            trim(
                (string) Config::get(
                    'session.same_site',
                    'Lax'
                )
            )
        );

        if (
            !in_array(
                $sameSite,
                [
                    'lax',
                    'strict',
                    'none',
                ],
                true
            )
        ) {
            throw new ConfigurationException(
                'SESSION_SAME_SITE deve ser Lax, Strict ou None.'
            );
        }

        if (
            $sameSite === 'none'
            && !$secure
        ) {
            throw new ConfigurationException(
                'SESSION_SAME_SITE=None exige SESSION_SECURE=true.'
            );
        }

        if ($environment !== 'production') {
            return;
        }

        if (!$secure) {
            throw new ConfigurationException(
                'SESSION_SECURE deve ser true em produção.'
            );
        }

        if (!$httpOnly) {
            throw new ConfigurationException(
                'SESSION_HTTP_ONLY deve ser true em produção.'
            );
        }
    }
}
