<?php

namespace nucleo\exceptions\http;

use nucleo\exceptions\GatovelException;

class TooManyRequestsException extends GatovelException
{
    public function __construct(
        string $message = 'Too Many Requests.'
    ) {
        parent::__construct(
            $message,
            429
        );
    }
}
