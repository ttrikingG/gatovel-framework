<?php

namespace nucleo\exceptions\http;

use nucleo\exceptions\GatovelException;

class ConflictException extends GatovelException
{
    public function __construct(
        string $message = 'Conflict.'
    ) {
        parent::__construct(
            $message,
            409
        );
    }
}
