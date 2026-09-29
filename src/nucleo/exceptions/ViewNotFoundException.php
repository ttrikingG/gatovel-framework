<?php

namespace nucleo\exceptions;

class ViewNotFoundException extends GatovelException
{
    public function __construct(
        string $view,
        string $path
    ) {
        parent::__construct(
            "View não encontrada: {$view}. Caminho esperado: {$path}",
            500
        );
    }
}
