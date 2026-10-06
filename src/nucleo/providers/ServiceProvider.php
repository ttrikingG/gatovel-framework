<?php

namespace nucleo\providers;

use nucleo\container\Container;

abstract class ServiceProvider
{
    public function __construct(
        protected Container $container
    ) {
    }

    public function register(): void
    {
    }

    public function boot(): void
    {
    }
}
