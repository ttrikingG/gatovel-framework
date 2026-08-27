<?php

namespace app\controllers\site;

use app\controllers\Controller;
use nucleo\loadSupport\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return $this->view(
            'Home',
            [
                'title' => 'Minha Home',
                'message' => 'View funcionando corretamente!',
                'user' => 'Tom'
            ]
        );
    }
}

