<?php

namespace nucleo\loadSystem;

use nucleo\exceptions\ControllerNotFoundException;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Router;

class StageOne
{
    public function load(
        Request $request
    ): object {
        $controller = Router::resolve(
            $request
        );

        if (!class_exists($controller)) {
            throw new ControllerNotFoundException(
                $controller
            );
        }

        return new $controller();
    }
}