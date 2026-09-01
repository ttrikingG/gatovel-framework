<?php

namespace app\controllers;

use nucleo\loadSupport\Response;
use nucleo\loadSupport\View;

abstract class Controller
{
    protected function json(
        mixed $data,
        int $status = 200
    ): Response {
        return Response::json(
            $data,
            $status
        );
    }

    protected function redirect(
        string $url,
        int $status = 302
    ): Response {
        return Response::redirect(
            $url,
            $status
        );
    }

    protected function view(
        string $view,
        array $data = [],
        int $status = 200,
        string $layout = 'App'
    ): Response {
        return Response::html(
            View::render(
                $view,
                $data,
                $layout
            ),
            $status
        );
    }
}

