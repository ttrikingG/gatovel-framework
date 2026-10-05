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
        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;

        if (!$this->isAllowedOrigin($origin)) {
            return $next(
                $request
            );
        }

        if ($this->isPreflightRequest($request)) {
            return $this->addCorsHeaders(
                Response::noContent(),
                $origin
            );
        }

        $response = $next(
            $request
        );

        return $this->addCorsHeaders(
            $response,
            $origin
        );
    }

    private function isAllowedOrigin(
        ?string $origin
    ): bool {
        if ($origin === null) {
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

    private function isPreflightRequest(
        Request $request
    ): bool {
        if (
            $request->method() !== 'OPTIONS'
            || !isset(
                $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']
            )
        ) {
            return false;
        }

        $requestedMethod = strtoupper(
            trim(
                $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']
            )
        );

        $allowedMethods = Config::get(
            'cors.allowed_methods',
            []
        );

        if (
            !in_array(
                $requestedMethod,
                $allowedMethods,
                true
            )
        ) {
            return false;
        }

        return $this->areRequestedHeadersAllowed();
    }

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
            'strtolower',
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
                !in_array(
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
