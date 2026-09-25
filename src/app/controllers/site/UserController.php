<?php

namespace app\controllers\site;

use app\controllers\Controller;
use app\models\User;
use nucleo\auth\authentication\Password;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class UserController extends Controller
{
    public function show(
        Request $request,
        object $parameters
    ): Response {

        $user = User::find(
            $parameters->id
        );

        if ($user === null) {
            return Response::json(
                [
                    'error' => 'Usuário não encontrado.'
                ],
                404
            );
        }

        return Response::json(
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
    }

    public function create(
        Request $request
    ): Response {

        $name = $request->post(
            'name',
            ''
        );

        $email = $request->post(
            'email',
            ''
        );

        $password = $request->post(
            'password',
            ''
        );

        if (
            !is_string($name)
            || trim($name) === ''
        ) {
            return Response::json(
                [
                    'error' => 'O nome é obrigatório.'
                ],
                422
            );
        }

        if (
            !is_string($email)
            || trim($email) === ''
        ) {
            return Response::json(
                [
                    'error' => 'O e-mail é obrigatório.'
                ],
                422
            );
        }

        if (
            !is_string($password)
            || strlen($password) < 8
        ) {
            return Response::json(
                [
                    'error' => 'A senha deve possuir pelo menos 8 caracteres.'
                ],
                422
            );
        }

        $email = trim($email);

        $existingUser = User::where(
            'email',
            $email
        )->first();

        if ($existingUser !== null) {
            return Response::json(
                [
                    'error' => 'Este e-mail já está cadastrado.'
                ],
                422
            );
        }

        $user = new User();

        $user->name = trim($name);
        $user->email = $email;
        $user->password = Password::hash(
            $password
        );

        $user->save();

        return Response::json(
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            201
        );
    }

    public function update(
        Request $request,
        object $parameters
    ): Response {

        $user = User::find(
            $parameters->id
        );

        if ($user === null) {
            return Response::json(
                [
                    'error' => 'Usuário não encontrado.'
                ],
                404
            );
        }

        $name = $request->post(
            'name',
            $user->name
        );

        $email = $request->post(
            'email',
            $user->email
        );

        $password = $request->post(
            'password',
            null
        );

        if (
            !is_string($name)
            || trim($name) === ''
        ) {
            return Response::json(
                [
                    'error' => 'O nome é obrigatório.'
                ],
                422
            );
        }

        if (
            !is_string($email)
            || trim($email) === ''
        ) {
            return Response::json(
                [
                    'error' => 'O e-mail é obrigatório.'
                ],
                422
            );
        }

        $email = trim($email);

        $existingUser = User::where(
            'email',
            $email
        )->first();

        if (
            $existingUser !== null
            && $existingUser['id'] !== $user->id
        ) {
            return Response::json(
                [
                    'error' => 'Este e-mail já está cadastrado.'
                ],
                422
            );
        }

        if (
            $password !== null
            && (
                !is_string($password)
                || strlen($password) < 8
            )
        ) {
            return Response::json(
                [
                    'error' => 'A senha deve possuir pelo menos 8 caracteres.'
                ],
                422
            );
        }

        $user->name = trim($name);
        $user->email = $email;

        if ($password !== null) {
            $user->password = Password::hash(
                $password
            );
        }

        $user->save();

        return Response::json(
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        );
    }

    public function delete(
        Request $request,
        object $parameters
    ): Response {

        $user = User::find(
            $parameters->id
        );

        if ($user === null) {
            return Response::json(
                [
                    'error' => 'Usuário não encontrado.'
                ],
                404
            );
        }

        $user->delete();

        return Response::json(
            [
                'message' => 'Usuário excluído com sucesso.'
            ]
        );
    }
}