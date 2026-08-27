<?php

require_once __DIR__ . '/../bootstrap.php';

use nucleo\loadSystem\StageOne;
use nucleo\loadSystem\StageTwo;
use nucleo\loadSystem\StageThree;
use nucleo\loadSupport\Response;
use nucleo\loadSupport\ErrorHandler;

try {

    $controller = (new StageOne())->load();

    $method = (new StageTwo())->load($controller);

    $parameters = (new StageThree())->load();

    if ($parameters === null) {

        $response = $controller->$method();

    } else {

        $response = $controller->$method(
            $parameters
        );
    }

    if (!$response instanceof Response) {
        throw new \Exception(
            'O controller deve retornar uma instância de Response.'
        );
    }

    $response->send();

} catch (\Throwable $exception) {

    ErrorHandler::handle($exception);
}