<?php

namespace app\controllers\auth;

use app\controllers\Controller;
use nucleo\auth\authentication\Auth;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class AuthController extends Controller
{
    public function showLogin(
        Request $request
    ): Response {
        return $this->view(
            'auth.login'
        );
    }

    public function login(
        Request $request
    ): Response {
        $email = $request->input(
            'email',
            ''
        );

        $password = $request->input(
            'password',
            ''
        );

        if (
            !is_string($email)
            || !is_string($password)
        ) {
            return $this->view(
                'auth.login',
                [
                    'error' =>
                        'E-mail ou senha inválidos.',
                ]
            );
        }

        if (
            !Auth::attempt(
                $email,
                $password
            )
        ) {
            if (
                Auth::emailVerificationRequired()
            ) {
                return $this->view(
                    'auth.login',
                    [
                        'error' =>
                            'Seu e-mail ainda não foi verificado.',
                    ]
                );
            }

            return $this->view(
                'auth.login',
                [
                    'error' =>
                        'E-mail ou senha inválidos.',
                ]
            );
        }

        if (Auth::mfaRequired()) {
            return Response::redirect(
                '/mfa'
            );
        }

        return Response::redirect(
            '/home'
        );
    }

    public function logout(
        Request $request
    ): Response {
        Auth::logout();

        return Response::redirect(
            '/login'
        );
    }
}