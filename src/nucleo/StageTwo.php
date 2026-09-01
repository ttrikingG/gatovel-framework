<?php

namespace nucleo\loadSystem;

use nucleo\loadSupport\Router;

class StageTwo
{
    public function load(object $controller): string
    {
        $method = Router::method();

        if (
            !preg_match(
                '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
                $method
            )
        ) {
            throw new \Exception(
                'Método inválido.'
            );
        }

        if (!method_exists($controller, $method)) {
            throw new \Exception(
                "Método {$method} não existe no controller."
            );
        }

        return $method;
    }
}