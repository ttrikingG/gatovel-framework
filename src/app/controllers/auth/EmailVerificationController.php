<?php

namespace app\controllers\auth;

use app\controllers\Controller;
use nucleo\auth\verification\EmailVerification;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class EmailVerificationController extends Controller
{
    public function verify(
        Request $request,
        object $parameters
    ): Response {
        $token = $parameters->token ?? '';

        if (
            !is_string($token)
            || $token === ''
        ) {
            return $this->view(
                'auth.verify-email',
                [
                    'error' =>
                        'O link de verificação é inválido '
                        . 'ou expirou.',
                ]
            );
        }

        if (
            !EmailVerification::verifyToken(
                $token
            )
        ) {
            return $this->view(
                'auth.verify-email',
                [
                    'error' =>
                        'O link de verificação é inválido '
                        . 'ou expirou.',
                ]
            );
        }

        return $this->view(
            'auth.verify-email',
            [
                'success' =>
                    'Seu e-mail foi verificado com sucesso.',
            ]
        );
    }

    public function showResend(
        Request $request
    ): Response {
        return $this->view(
            'auth.resend-verification'
        );
    }

    public function resend(
        Request $request
    ): Response {
        $email = $request->input(
            'email',
            ''
        );

        if (
            !is_string($email)
            || trim($email) === ''
        ) {
            return $this->view(
                'auth.resend-verification',
                [
                    'error' =>
                        'Informe seu e-mail.',
                ]
            );
        }

        EmailVerification::resend(
            $email
        );

        return $this->view(
            'auth.resend-verification',
            [
                'success' =>
                    'Se o e-mail estiver cadastrado '
                    . 'e ainda não tiver sido verificado, '
                    . 'um novo link de verificação '
                    . 'será enviado.',
            ]
        );
    }
}