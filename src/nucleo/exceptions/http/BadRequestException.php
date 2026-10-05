<?php

namespace nucleo\exceptions\http;

use nucleo\exceptions\GatovelException;

class BadRequestException extends GatovelException
{
    public function __construct(
        string $message = 'Bad Request.'
    ) {
        parent::__construct(
            $message,
            400
        );
    }
}
