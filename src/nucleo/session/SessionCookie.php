<?php

namespace nucleo\session;

use RuntimeException;
use nucleo\config\Config;

class SessionCookie
{
    public static function configure(): void
    {
        $sameSite = self::sameSite();

        $secure = (bool) Config::get(
            'session.secure',
            false
        );

        if (
            $sameSite === 'None'
            && !$secure
        ) {
            throw new RuntimeException(
                'SameSite=None exige cookie Secure.'
            );
        }

        $path = (string) Config::get(
            'session.path',
            '/'
        );

        if ($path === '') {
            $path = '/';
        }

        $domain = (string) Config::get(
            'session.domain',
            ''
        );

        $httpOnly = (bool) Config::get(
            'session.http_only',
            true
        );

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $sameSite,
        ]);
    }

    public static function delete(): void
    {
        if (!ini_get('session.use_cookies')) {
            return;
        }

        $params = session_get_cookie_params();

        $deleted = setcookie(
            session_name(),
            '',
            [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'],
            ]
        );

        if (!$deleted) {
            throw new RuntimeException(
                'Não foi possível remover o cookie da sessão.'
            );
        }
    }

    private static function sameSite(): string
    {
        $sameSite = ucfirst(
            strtolower(
                trim(
                    (string) Config::get(
                        'session.same_site',
                        'Lax'
                    )
                )
            )
        );

        if (
            !in_array(
                $sameSite,
                [
                    'Lax',
                    'Strict',
                    'None',
                ],
                true
            )
        ) {
            throw new RuntimeException(
                'SESSION_SAME_SITE deve ser Lax, Strict ou None.'
            );
        }

        return $sameSite;
    }
}
