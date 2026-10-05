<?php

namespace nucleo\exceptions\view;

use nucleo\exceptions\GatovelException;

class LayoutNotFoundException extends GatovelException
{
    public function __construct(
        string $layout,
        string $path
    ) {
        parent::__construct(
            "Layout não encontrado: {$layout}. Caminho esperado: {$path}",
            500
        );
    }
}
