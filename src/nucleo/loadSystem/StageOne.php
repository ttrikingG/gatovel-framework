<?php

namespace nucleo\loadSystem;

use nucleo\exceptions\controller\ControllerNotFoundException;
use nucleo\http\Request;
use nucleo\routing\Router;

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