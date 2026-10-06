<?php

namespace app\controllers;

use nucleo\http\Request;
use nucleo\http\Response;
use nucleo\security\Csp;
use nucleo\view\View;

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
        string $layout = 'App',
        ?Request $request = null
    ): Response {
        if ($request !== null) {
            $data['cspNonce'] = Csp::htmlNonce(
                $request
            );
        }

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

