<?php

namespace nucleo\exceptions\http;

use nucleo\exceptions\GatovelException;

class NotFoundException extends GatovelException
{
    public function __construct(
        string $message = 'Not Found.'
    ) {
        parent::__construct(
            $message,
            404
        );
    }
}
