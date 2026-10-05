<?php

namespace nucleo\exceptions\routing;

use nucleo\exceptions\GatovelException;

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
