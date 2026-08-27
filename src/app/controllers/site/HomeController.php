<?php

namespace app\controllers\site;

use nucleo\loadSupport\Response;

class HomeController
{
    public function index(): Response
    {
        return Response::html(
            '<h1>Response funcionando!</h1>'
        );
    }

    public function teste(object $parameters): Response
    {
        return Response::json([
            'status' => 'ok',
            'id' => $parameters->id
        ]);
    }
}