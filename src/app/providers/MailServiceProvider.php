<?php

namespace app\providers;

use RuntimeException;
use nucleo\config\Config;
use nucleo\mail\Mail;
use nucleo\mail\transport\LogMailTransport;

class MailServiceProvider
{
    public static function boot(): void
    {
        $transport = Config::get(
            'mail.transport',
            'log'
        );

        switch ($transport) {

            case 'log':

                Mail::setTransport(
                    new LogMailTransport(
                        Config::get('mail.log.path')
                    )
                );

                break;

            default:

                throw new RuntimeException(
                    "Mail transport não suportado: {$transport}"
                );
        }
    }
}