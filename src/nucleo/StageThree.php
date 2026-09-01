<?php

namespace nucleo\loadSystem;

use nucleo\loadSupport\Router;

class StageThree
{
    public function load(): ?object
    {
        $parameters = Router::parameters();

        if (empty($parameters)) {
            return null;
        }

        return (object) $parameters;
    }
}