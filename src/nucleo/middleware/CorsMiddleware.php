<?php

namespace nucleo\middleware;

use nucleo\config\Config;
use nucleo\http\Request;
use nucleo\http\Response;

class CorsMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {
        $origin = $_SERVER['HTTP_ORIGIN']
            ?? null;

        if ($this->isPreflightRequest($request)) {
            return $this->handlePreflight(
                $origin
            );
        }

        $response = $next(
            $request
        );

        if (!$this->isAllowedOrigin($origin)) {
            return $response;
        }

        return $this->addCorsHeaders(
            $response,
            $origin
        );
    }

    /**
     * Verifica se a requisição representa
     * uma negociação CORS preflight.
     */
    private function isPreflightRequest(
        Request $request
    ): bool {
        return $request->method() === 'OPTIONS'
            && isset(
                $_SERVER[
                    'HTTP_ACCESS_CONTROL_REQUEST_METHOD'
                ]
            );
    }

    /**
     * Processa uma requisição preflight sem encaminhá-la
     * para o restante do pipeline da aplicação.
     */
    private function handlePreflight(
        ?string $origin
    ): Response {
        if (
            !$this->isAllowedOrigin($origin)
            || !$this->isRequestedMethodAllowed()
            || !$this->areRequestedHeadersAllowed()
        ) {
            return Response::noContent()
                ->status(403);
        }

        return $this->addCorsHeaders(
            Response::noContent(),
            $origin
        );
    }

    /**
     * Verifica a origem através de comparação exata
     * com a allowlist configurada.
     */
    private function isAllowedOrigin(
        ?string $origin
    ): bool {
        if (
            $origin === null
            || trim($origin) === ''
        ) {
            return false;
        }

        $allowedOrigins = Config::get(
            'cors.allowed_origins',
            []
        );

        return in_array(
            $origin,
            $allowedOrigins,
            true
        );
    }

    /**
     * Valida o método solicitado pelo preflight.
     */
    private function isRequestedMethodAllowed(): bool
    {
        $requestedMethod = strtoupper(
            trim(
                $_SERVER[
                    'HTTP_ACCESS_CONTROL_REQUEST_METHOD'
                ] ?? ''
            )
        );

        if ($requestedMethod === '') {
            return false;
        }

        $allowedMethods = Config::get(
            'cors.allowed_methods',
            []
        );

        return in_array(
            $requestedMethod,
            $allowedMethods,
            true
        );
    }

    /**
     * Valida todos os headers solicitados pelo preflight.
     *
     * A comparação dos nomes é case-insensitive,
     * conforme a semântica dos headers HTTP.
     */
    private function areRequestedHeadersAllowed(): bool
    {
        $requestedHeaders = $_SERVER[
            'HTTP_ACCESS_CONTROL_REQUEST_HEADERS'
        ] ?? '';

        if (trim($requestedHeaders) === '') {
            return true;
        }

        $allowedHeaders = Config::get(
            'cors.allowed_headers',
            []
        );

        $normalizedAllowedHeaders = array_map(
            static fn (mixed $header): string =>
                strtolower(
                    trim(
                        (string) $header
                    )
                ),
            $allowedHeaders
        );

        foreach (
            explode(',', $requestedHeaders)
            as $requestedHeader
        ) {
            $requestedHeader = strtolower(
                trim(
                    $requestedHeader
                )
            );

            if (
                $requestedHeader === ''
                || !in_array(
                    $requestedHeader,
                    $normalizedAllowedHeaders,
                    true
                )
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Adiciona os headers CORS somente depois
     * que a origem foi explicitamente autorizada.
     */
    private function addCorsHeaders(
        Response $response,
        string $origin
    ): Response {
        $allowedMethods = Config::get(
            'cors.allowed_methods',
            []
        );

        $allowedHeaders = Config::get(
            'cors.allowed_headers',
            []
        );

        $allowCredentials = (bool) Config::get(
            'cors.allow_credentials',
            false
        );

        $response->header(
            'Access-Control-Allow-Origin',
            $origin
        );

        $response->header(
            'Vary',
            'Origin'
        );

        $response->header(
            'Access-Control-Allow-Methods',
            implode(
                ', ',
                $allowedMethods
            )
        );

        $response->header(
            'Access-Control-Allow-Headers',
            implode(
                ', ',
                $allowedHeaders
            )
        );

        if ($allowCredentials) {
            $response->header(
                'Access-Control-Allow-Credentials',
                'true'
            );
        }

        return $response;
    }
}
