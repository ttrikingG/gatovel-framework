<?php

namespace nucleo\exceptions\http;

use nucleo\exceptions\GatovelException;

class UnauthorizedException extends GatovelException
{
    public function __construct(
        string $message = 'Unauthorized.'
    ) {
        parent::__construct(
            $message,
            401
        );
    }
}
