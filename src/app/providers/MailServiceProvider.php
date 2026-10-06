<?php

namespace app\providers;

use RuntimeException;
use nucleo\config\Config;
use nucleo\mail\Mail;
use nucleo\mail\contracts\MailTransport;
use nucleo\mail\transport\LogMailTransport;
use nucleo\providers\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $transport = Config::get(
            'mail.transport',
            'log'
        );

        switch ($transport) {

            case 'log':

                $this->container->singleton(
                    MailTransport::class,
                    function (): MailTransport {
                        return new LogMailTransport(
                            Config::get('mail.log.path')
                        );
                    }
                );

                break;

            default:

                throw new RuntimeException(
                    "Mail transport não suportado: {$transport}"
                );
        }
    }

    public function boot(): void
    {
        Mail::setTransport(
            $this->container->get(
                MailTransport::class
            )
        );
    }
}
