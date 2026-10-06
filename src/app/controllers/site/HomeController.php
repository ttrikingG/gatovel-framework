<?php

namespace app\controllers\site;

use app\controllers\Controller;
use nucleo\http\Request;
use nucleo\http\Response;

class HomeController extends Controller
{
    public function index(
        Request $request
    ): Response {
        return $this->view(
            'home',
            [
                'title' => 'Gatovel Framework',
                'message' => 'Gatovel Framework is running.'
            ],
            200,
            'App',
            $request
        );
    }
}
