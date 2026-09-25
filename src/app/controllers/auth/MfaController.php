<?php

namespace app\controllers\auth;

use app\controllers\Controller;
use nucleo\auth\authentication\Auth;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class MfaController extends Controller
{
    public function show(
        Request $request
    ): Response {
        return $this->view(
            'auth.mfa'
        );
    }

    public function verify(
        Request $request
    ): Response {
        $code = $request->input(
            'code',
            ''
        );

        if (
            !is_string($code)
            || trim($code) === ''
        ) {
            return $this->view(
                'auth.mfa',
                [
                    'error' => 'Informe o código de autenticação.',
                ]
            );
        }

        if (!Auth::completeMfa($code)) {
            return $this->view(
                'auth.mfa',
                [
                    'error' => 'Código MFA inválido.',
                ]
            );
        }

        return Response::redirect(
            '/home'
        );
    }
}