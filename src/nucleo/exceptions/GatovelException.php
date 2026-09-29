<?php

namespace nucleo\exceptions;

use RuntimeException;
use Throwable;

class GatovelException extends RuntimeException
{
    protected int $statusCode = 500;

    public function __construct(
        string $message = '',
        int $statusCode = 500,
        ?Throwable $previous = null
    ) {
        $this->statusCode = $statusCode;

        parent::__construct(
            $message,
            $statusCode,
            $previous
        );
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}