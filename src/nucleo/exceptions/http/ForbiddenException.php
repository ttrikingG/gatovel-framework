<?php

namespace nucleo\exceptions\http;

use nucleo\exceptions\GatovelException;

class ForbiddenException extends GatovelException
{
    public function __construct(
        string $message = 'Forbidden.'
    ) {
        parent::__construct(
            $message,
            403
        );
    }
}
