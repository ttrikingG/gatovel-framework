<?php

namespace nucleo\exceptions\controller;

use nucleo\exceptions\GatovelException;

class MethodNotFoundException extends GatovelException
{
    public function __construct(
        string $method
    ) {
        parent::__construct(
            "Método não encontrado no controller: {$method}",
            500
        );
    }
}