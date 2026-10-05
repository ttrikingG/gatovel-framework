<?php

namespace nucleo\exceptions\controller;

use nucleo\exceptions\GatovelException;

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