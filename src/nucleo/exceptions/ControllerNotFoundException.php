<?php

namespace nucleo\exceptions;

class ControllerNotFoundException extends GatovelException
{
    public function __construct(
        string $controller
    ) {
        parent::__construct(
            "Controller não encontrado: {$controller}",
            500
        );
    }
}