<?php

namespace nucleo\loadSystem;

use nucleo\routing\Router;

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