<?php

namespace app\controllers\auth;

use app\controllers\Controller;
use nucleo\auth\password\PasswordRecovery;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class PasswordRecoveryController extends Controller
{
    public function showForgotPassword(
        Request $request
    ): Response {
        return $this->view(
            'auth.forgot-password'
        );
    }

    public function forgotPassword(
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
                'auth.forgot-password',
                [
                    'error' => 'Informe seu e-mail.',
                ]
            );
        }

        PasswordRecovery::create(
            $email
        );

        return $this->view(
            'auth.forgot-password',
            [
                'success' =>
                    'Se o e-mail estiver cadastrado, '
                    . 'você receberá as instruções para '
                    . 'recuperar sua senha.',
            ]
        );
    }

    public function showResetPassword(
        Request $request,
        object $parameters
    ): Response {
        $token = $parameters->token ?? '';

        if (
            !is_string($token)
            || $token === ''
            || !PasswordRecovery::resetTokenIsValid(
                $token
            )
        ) {
            return $this->view(
                'auth.reset-password',
                [
                    'error' =>
                        'O link de recuperação é inválido '
                        . 'ou expirou.',
                ]
            );
        }

        return $this->view(
            'auth.reset-password',
            [
                'token' => $token,
            ]
        );
    }

    public function resetPassword(
        Request $request,
        object $parameters
    ): Response {
        $token = $parameters->token ?? '';

        $password = $request->input(
            'password',
            ''
        );

        $passwordConfirmation = $request->input(
            'password_confirmation',
            ''
        );

        if (
            !is_string($token)
            || $token === ''
        ) {
            return $this->view(
                'auth.reset-password',
                [
                    'error' =>
                        'O link de recuperação é inválido '
                        . 'ou expirou.',
                ]
            );
        }

        if (
            !is_string($password)
            || !is_string($passwordConfirmation)
        ) {
            return $this->view(
                'auth.reset-password',
                [
                    'token' => $token,
                    'error' =>
                        'Dados inválidos.',
                ]
            );
        }

        if (
            $password !== $passwordConfirmation
        ) {
            return $this->view(
                'auth.reset-password',
                [
                    'token' => $token,
                    'error' =>
                        'As senhas não coincidem.',
                ]
            );
        }

        if (
            strlen($password) < 8
        ) {
            return $this->view(
                'auth.reset-password',
                [
                    'token' => $token,
                    'error' =>
                        'A senha deve possuir '
                        . 'pelo menos 8 caracteres.',
                ]
            );
        }

        if (
            !PasswordRecovery::reset(
                $token,
                $password
            )
        ) {
            return $this->view(
                'auth.reset-password',
                [
                    'error' =>
                        'O link de recuperação é inválido '
                        . 'ou expirou.',
                ]
            );
        }

        return Response::redirect(
            '/login'
        );
    }
}