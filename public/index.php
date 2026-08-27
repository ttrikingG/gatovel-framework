<?php

require_once __DIR__ . '/../bootstrap.php';

use nucleo\loadSystem\StageOne;
use nucleo\loadSystem\StageTwo;
use nucleo\loadSystem\StageThree;
use nucleo\loadSupport\ErrorHandler;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;
use nucleo\loadSupport\Router;
use nucleo\middleware\MiddlewareRunner;

try {

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

                return $controller->$method();

            }

            return $controller->$method(
                $parameters
            );
        }
    );

    $response->send();

} catch (\Throwable $exception) {

    ErrorHandler::handle($exception);
}