<?php

namespace nucleo\providers;

use nucleo\exceptions\ProviderException;

class ProviderLoader
{
    public static function load(array $providers): void
    {
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

            if (!method_exists($provider, 'boot')) {
                throw new ProviderException(
                    "O provider {$provider} deve possuir o método boot()."
                );
            }

            $provider::boot();
        }
    }
}
