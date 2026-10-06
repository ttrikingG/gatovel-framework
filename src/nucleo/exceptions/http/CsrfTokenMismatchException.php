<?php

namespace nucleo\exceptions\http;

use nucleo\exceptions\GatovelException;

class CsrfTokenMismatchException extends GatovelException
{
    public function __construct(
        string $message = 'CSRF Token Mismatch.'
    ) {
        parent::__construct(
            $message,
            419
        );
    }
}
