<?php

namespace nucleo\loadSystem;

use nucleo\exceptions\controller\MethodNotFoundException;
use nucleo\routing\Router;

class StageTwo
{
    public function load(
        object $controller
    ): string {
        $method = Router::method();

        if (
            !method_exists(
                $controller,
                $method
            )
        ) {
            throw new MethodNotFoundException(
                $method
            );
        }

        return $method;
    }
}