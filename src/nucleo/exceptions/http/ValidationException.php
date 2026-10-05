<?php

namespace nucleo\exceptions\http;

use nucleo\exceptions\GatovelException;

class ValidationException extends GatovelException
{
    private array $errors;

    public function __construct(
        array $errors,
        string $message = 'Validation failed.'
    ) {
        $this->errors = $errors;

        parent::__construct(
            $message,
            422
        );
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
