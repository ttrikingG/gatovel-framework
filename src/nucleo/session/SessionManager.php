<?php

namespace nucleo\session;

use RuntimeException;
use nucleo\config\Config;

class SessionManager
{
    private const LAST_ACTIVITY_KEY = '_gatovel_last_activity';

    public static function start(): void
    {
        if (!self::isStarted()) {
            if (headers_sent()) {
                throw new RuntimeException(
                    'Não foi possível iniciar a sessão porque os headers já foram enviados.'
                );
            }

            self::configure();

            if (!session_start()) {
                throw new RuntimeException(
                    'Não foi possível iniciar a sessão.'
                );
            }

            Flash::reset();

            self::validateIdleTimeout();
        }

        Flash::prepare();
    }

    public static function isStarted(): bool
    {
        return session_status()
            === PHP_SESSION_ACTIVE;
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

        if (!session_write_close()) {
            throw new RuntimeException(
                'Não foi possível finalizar a sessão.'
            );
        }

        Flash::reset();
    }

    public static function destroy(): void
    {
        if (!self::isStarted()) {
            return;
        }

        $_SESSION = [];

        SessionCookie::delete();

        if (!session_destroy()) {
            throw new RuntimeException(
                'Não foi possível destruir a sessão.'
            );
        }

        Flash::reset();
    }

    private static function validateIdleTimeout(): void
    {
        $now = time();

        $timeout = max(
            1,
            (int) Config::get(
                'session.idle_timeout',
                30
            )
        ) * 60;

        $lastActivity = $_SESSION[self::LAST_ACTIVITY_KEY]
            ?? null;

        if (
            $lastActivity !== null
            && (
                !is_int($lastActivity)
                || $lastActivity > $now
                || ($now - $lastActivity) > $timeout
            )
        ) {
            self::invalidateExpiredSession();
        }

        $_SESSION[self::LAST_ACTIVITY_KEY] = $now;
    }

    private static function invalidateExpiredSession(): void
    {
        $_SESSION = [];

        if (!session_regenerate_id(true)) {
            throw new RuntimeException(
                'Não foi possível regenerar a sessão expirada.'
            );
        }

        Flash::reset();
    }

    private static function configure(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }

        $name = trim(
            (string) Config::get(
                'session.name',
                'gatovel_session'
            )
        );

        if ($name === '') {
            throw new RuntimeException(
                'O nome da sessão não pode ser vazio.'
            );
        }

        $lifetime = max(
            1,
            (int) Config::get(
                'session.lifetime',
                120
            )
        );

        if (!session_name($name)) {
            throw new RuntimeException(
                'Não foi possível definir o nome da sessão.'
            );
        }

        if (
            ini_set(
                'session.use_strict_mode',
                '1'
            ) === false
        ) {
            throw new RuntimeException(
                'Não foi possível habilitar session.use_strict_mode.'
            );
        }

        if (
            ini_set(
                'session.use_only_cookies',
                '1'
            ) === false
        ) {
            throw new RuntimeException(
                'Não foi possível habilitar session.use_only_cookies.'
            );
        }

        if (
            ini_set(
                'session.use_trans_sid',
                '0'
            ) === false
        ) {
            throw new RuntimeException(
                'Não foi possível desabilitar session.use_trans_sid.'
            );
        }

        if (
            ini_set(
                'session.gc_maxlifetime',
                (string) ($lifetime * 60)
            ) === false
        ) {
            throw new RuntimeException(
                'Não foi possível configurar o tempo de vida da sessão.'
            );
        }

        SessionCookie::configure();
    }
}
