<?php

require_once __DIR__ . '/../vendor/autoload.php';

use nucleo\config\Config;
use nucleo\errors\ErrorHandler;
use nucleo\loadSystem\StageOne;
use nucleo\loadSystem\StageTwo;
use nucleo\loadSystem\StageThree;
use nucleo\http\Request;
use nucleo\http\Response;
use nucleo\routing\Router;
use nucleo\middleware\MiddlewareRunner;
use nucleo\session\Session;

$request = null;

try {

    require_once __DIR__ . '/../bootstrap.php';

    Session::start();

    $request = new Request();

    $globalMiddlewares = Config::get(
        'app.middlewares',
        []
    );

    $response = MiddlewareRunner::run(
        $request,
        $globalMiddlewares,
        function (Request $request) use ($container): Response {

            try {

                $controller = (new StageOne(
                    $container
                ))->load(
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
                    },
                    $container
                );

            } catch (\Throwable $exception) {

                return ErrorHandler::render(
                    $exception,
                    $request
                );
            }
        },
        $container
    );

    $response->send();

} catch (\Throwable $exception) {

    ErrorHandler::handle(
        $exception,
        $request
    );

} finally {

    Session::close();
}
