<?php

require_once __DIR__ . '/../vendor/autoload.php';

use nucleo\config\Config;
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

    $globalMiddlewares = Config::get(
        'app.middlewares',
        []
    );

    $response = MiddlewareRunner::run(
        $request,
        $globalMiddlewares,
        function (Request $request): Response {

            try {

                $controller = (new StageOne())->load(
                    $request
                );

                $method = (new StageTwo())->load(
                    $controller
                );

                $parameters = (new StageThree())->load();

                $route = Router::currentRoute();

                $middlewares = $route['middlewares'] ?? [];

                return MiddlewareRunner::run(
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

            } catch (\Throwable $exception) {

                return ErrorHandler::render(
                    $exception,
                    $request
                );
            }
        }
    );

    $response->send();

} catch (\Throwable $exception) {

    ErrorHandler::handle(
        $exception,
        $request
    );
}
