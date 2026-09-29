<?php

namespace nucleo\exceptions;

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
