<?php

namespace app\controllers\site;

use app\controllers\Controller;
use nucleo\auth\authentication\Auth;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class HomeController extends Controller
{
    public function index(
        Request $request
    ): Response {
        $user = Auth::user();

        return $this->view(
            'home',
            [
                'title' => 'Minha Home',
                'message' => 'View funcionando corretamente!',
                'user' => $user
            ]
        );
    }
}