<?php

namespace app\middlewares;

use nucleo\middleware\Middleware;
use nucleo\auth\mfa\MfaChallenge;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class MfaChallengeMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {
        if (!MfaChallenge::has()) {
            return Response::json(
                [
                    'error' => 'Nenhum desafio MFA está pendente.',
                ],
                403
            );
        }

        return $next($request);
    }
}