<?php

namespace nucleo\exceptions;

class MethodNotAllowedException extends GatovelException
{
    public function __construct(
        string $method,
        string $uri
    ) {
        parent::__construct(
            "Método HTTP não permitido: {$method} {$uri}",
            405
        );
    }
}
