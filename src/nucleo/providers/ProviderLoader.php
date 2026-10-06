<?php

namespace nucleo\providers;

use nucleo\container\Container;
use nucleo\exceptions\provider\ProviderException;

class ProviderLoader
{
    public static function load(
        array $providers,
        Container $container
    ): void {
        $instances = [];

        foreach ($providers as $provider) {

            if (!is_string($provider)) {
                throw new ProviderException(
                    'O provider deve ser informado como uma classe.'
                );
            }

            if (!class_exists($provider)) {
                throw new ProviderException(
                    "Provider não encontrado: {$provider}"
                );
            }

            if (!is_subclass_of($provider, ServiceProvider::class)) {
                throw new ProviderException(
                    "O provider {$provider} deve estender "
                    . ServiceProvider::class
                    . '.'
                );
            }

            $instances[] = $container->get(
                $provider
            );
        }

        foreach ($instances as $provider) {
            $provider->register();
        }

        foreach ($instances as $provider) {
            $provider->boot();
        }
    }
}
