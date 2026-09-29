<?php

namespace nucleo\exceptions;

class InvalidRouteDefinitionException extends GatovelException
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
