<?php

namespace nucleo\loadSystem;

use nucleo\loadSupport\Request;
use nucleo\loadSupport\Router;

class StageOne
{
    public function load(Request $request): object
    {
        $controller = Router::resolve($request);

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

