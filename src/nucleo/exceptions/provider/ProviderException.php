<?php

namespace nucleo\exceptions\provider;

use nucleo\exceptions\GatovelException;

class ProviderException extends GatovelException
{
    public function __construct(
        string $message
    ) {
        parent::__construct(
            $message,
            500
        );
    }
}
