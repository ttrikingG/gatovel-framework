<?php

require_once __DIR__ . '/../vendor/autoload.php';

use nucleo\errors\ErrorHandler;
use nucleo\loadSystem\StageOne;
use nucleo\loadSystem\StageTwo;
use nucleo\loadSystem\StageThree;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;
use nucleo\loadSupport\Router;
use nucleo\middleware\MiddlewareRunner;

$request = null;

try {

    require_once __DIR__ . '/../bootstrap.php';

    $request = new Request();

    $controller = (new StageOne())->load(
        $request
    );

    $method = (new StageTwo())->load(
        $controller
    );

    $parameters = (new StageThree())->load();

    $route = Router::currentRoute();

    $middlewares = $route['middlewares'] ?? [];

    $response = MiddlewareRunner::run(
        $request,
        $middlewares,
        function (Request $request) use (
            $controller,
            $method,
            $parameters
        ): Response {

            if ($parameters === null) {

                return $controller->$method(
                    $request
                );
            }

            return $controller->$method(
                $request,
                $parameters
            );
        }
    );

    $response->send();

} catch (\Throwable $exception) {

    ErrorHandler::handle(
        $exception,
        $request
    );
}