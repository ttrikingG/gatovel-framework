<?php

namespace app\controllers\auth;

use app\controllers\Controller;
use app\models\User;
use nucleo\auth\authentication\Auth;
use nucleo\auth\mfa\Mfa;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class MfaSetupController extends Controller
{
    public function show(
        Request $request
    ): Response {
        $user = Auth::user();

        if (!($user instanceof User)) {
            return Response::redirect(
                '/login'
            );
        }

        if (Mfa::isEnabled($user)) {
            return $this->view(
                'auth.mfa-setup',
                [
                    'enabled' => true,
                ]
            );
        }

        $setup = Mfa::setup(
            $user
        );

        return $this->view(
            'auth.mfa-setup',
            [
                'enabled' => false,
                'secret' => $setup['secret'],
            ]
        );
    }

    public function regenerate(
        Request $request
    ): Response {
        $user = Auth::user();

        if (!($user instanceof User)) {
            return Response::redirect(
                '/login'
            );
        }

        if (Mfa::isEnabled($user)) {
            return $this->view(
                'auth.mfa-setup',
                [
                    'enabled' => true,
                    'error' => 'O MFA já está ativado.',
                ]
            );
        }

        $setup = Mfa::regenerate(
            $user
        );

        return $this->view(
            'auth.mfa-setup',
            [
                'enabled' => false,
                'secret' => $setup['secret'],
            ]
        );
    }

    public function enable(
        Request $request
    ): Response {
        $user = Auth::user();

        if (!($user instanceof User)) {
            return Response::redirect(
                '/login'
            );
        }

        $code = $request->input(
            'code',
            ''
        );

        if (
            !is_string($code)
            || trim($code) === ''
        ) {
            $setup = Mfa::setup(
                $user
            );

            return $this->view(
                'auth.mfa-setup',
                [
                    'enabled' => false,
                    'secret' => $setup['secret'],
                    'error' => 'Informe o código do autenticador.',
                ]
            );
        }

        if (
            !Mfa::enable(
                $user,
                trim($code)
            )
        ) {
            $setup = Mfa::setup(
                $user
            );

            return $this->view(
                'auth.mfa-setup',
                [
                    'enabled' => false,
                    'secret' => $setup['secret'],
                    'error' => 'Código inválido. Tente novamente.',
                ]
            );
        }

        return Response::redirect(
            '/home'
        );
    }
}