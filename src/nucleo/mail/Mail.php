<?php

namespace nucleo\mail;

use nucleo\mail\contracts\MailTransport;

class Mail
{
    private static ?MailTransport $transport = null;

    public static function setTransport(
        MailTransport $transport
    ): void {
        self::$transport = $transport;
    }

    public static function send(
        Message $message
    ): bool {
        if (self::$transport === null) {
            return false;
        }

        return self::$transport->send(
            $message
        );
    }
}