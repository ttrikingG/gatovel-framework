<?php

namespace nucleo\exceptions;

class RouteNotFoundException extends GatovelException
{
    public function __construct(
        string $method,
        string $uri
    ) {
        parent::__construct(
            "Rota não encontrada: {$method} {$uri}",
            404
        );
    }
}
