<?php

namespace app\middlewares;

use app\authorization\Authorization;
use Gatovel\Database\orm\ActiveRecord;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;
use nucleo\loadSupport\Router;
use nucleo\middleware\Middleware;

class AuthorizationMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {

        $route = Router::currentRoute();

        $authorization = $route['authorization'] ?? null;

        if ($authorization === null) {
            throw new \RuntimeException(
                'Configuração de autorização não encontrada para a rota.'
            );
        }

        $ability = $authorization['ability'] ?? null;
        $resource = $authorization['resource'] ?? null;
        $model = $authorization['model'] ?? null;
        $parameter = $authorization['parameter'] ?? null;

        if (
            !is_string($ability)
            || $ability === ''
        ) {
            throw new \RuntimeException(
                'A habilidade de autorização não foi definida.'
            );
        }

        if (
            !is_string($resource)
            || $resource === ''
        ) {
            throw new \RuntimeException(
                'O recurso de autorização não foi definido.'
            );
        }

        /*
         * Autorização sem recurso-alvo.
         *
         * Exemplo:
         *
         * POST /users
         *
         * users.create
         */
        if (
            $model === null
            && $parameter === null
        ) {
            if (
                !Authorization::allows(
                    $ability,
                    null,
                    $resource
                )
            ) {
                return Response::json(
                    [
                        'error' => 'Acesso negado.'
                    ],
                    403
                );
            }

            return $next($request);
        }

        if (
            !is_string($model)
            || $model === ''
        ) {
            throw new \RuntimeException(
                'O model de autorização não foi definido.'
            );
        }

        if (
            !is_string($parameter)
            || $parameter === ''
        ) {
            throw new \RuntimeException(
                'O parâmetro da rota não foi definido.'
            );
        }

        if (!class_exists($model)) {
            throw new \RuntimeException(
                "O model {$model} não existe."
            );
        }

        if (
            !is_a(
                $model,
                ActiveRecord::class,
                true
            )
        ) {
            throw new \RuntimeException(
                "O model {$model} precisa estender ActiveRecord."
            );
        }

        $parameters = Router::parameters();

        $identifier = $parameters[$parameter] ?? null;

        if ($identifier === null) {
            throw new \RuntimeException(
                "O parâmetro {$parameter} não foi encontrado na rota."
            );
        }

        $target = $model::find($identifier);

        if ($target === null) {
            return Response::json(
                [
                    'error' => 'Recurso não encontrado.'
                ],
                404
            );
        }

        if (
            !Authorization::allows(
                $ability,
                $target
            )
        ) {
            return Response::json(
                [
                    'error' => 'Acesso negado.'
                ],
                403
            );
        }

        return $next($request);
    }
}