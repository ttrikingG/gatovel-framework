<?php

namespace nucleo\exceptions\routing;

use nucleo\exceptions\GatovelException;

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
