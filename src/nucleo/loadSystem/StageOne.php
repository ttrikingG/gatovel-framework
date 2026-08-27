<?php

namespace nucleo\loadSystem;

use nucleo\loadSupport\Router;

class StageOne
{
    public function load(): object
    {
        $controller = Router::resolve();

        if (
            !str_starts_with(
                $controller,
                'app\\controllers\\'
            )
        ) {
            throw new \Exception(
                'Controller inválido.'
            );
        }

        if (!class_exists($controller)) {
            throw new \Exception(
                "A classe {$controller} não existe."
            );
        }

        return new $controller();
    }
}