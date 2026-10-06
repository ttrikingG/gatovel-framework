<?php

namespace nucleo\middleware;

use nucleo\config\Config;
use nucleo\http\Request;
use nucleo\http\Response;
use nucleo\security\Csp;

class SecurityHeadersMiddleware extends Middleware
{
    public function handle(
        Request $request,
        callable $next
    ): Response {
        $cspEnabled = (bool) Config::get(
            'security.csp.enabled',
            true
        );

        if ($cspEnabled) {
            Csp::nonce(
                $request
            );
        }

        $response = $next(
            $request
        );

        $this->addStandardHeaders(
            $response
        );

        if ($cspEnabled) {
            $response->header(
                'Content-Security-Policy',
                Csp::policy($request)
            );
        }

        if ($this->isHttps()) {
            $this->addHstsHeader(
                $response
            );
        }

        return $response;
    }

    private function addStandardHeaders(
        Response $response
    ): void {
        $contentTypeOptions = Config::get(
            'security.headers.content_type_options',
            'nosniff'
        );

        if (
            is_string($contentTypeOptions)
            && $contentTypeOptions !== ''
        ) {
            $response->header(
                'X-Content-Type-Options',
                $contentTypeOptions
            );
        }

        $referrerPolicy = Config::get(
            'security.headers.referrer_policy',
            'strict-origin-when-cross-origin'
        );

        if (
            is_string($referrerPolicy)
            && $referrerPolicy !== ''
        ) {
            $response->header(
                'Referrer-Policy',
                $referrerPolicy
            );
        }

        $frameOptions = Config::get(
            'security.headers.frame_options',
            'DENY'
        );

        if (
            is_string($frameOptions)
            && $frameOptions !== ''
        ) {
            $response->header(
                'X-Frame-Options',
                $frameOptions
            );
        }

        $permissionsPolicy = Config::get(
            'security.headers.permissions_policy',
            ''
        );

        if (
            is_string($permissionsPolicy)
            && $permissionsPolicy !== ''
        ) {
            $response->header(
                'Permissions-Policy',
                $permissionsPolicy
            );
        }
    }

    private function addHstsHeader(
        Response $response
    ): void {
        if (
            !(bool) Config::get(
                'security.hsts.enabled',
                true
            )
        ) {
            return;
        }

        $maxAge = max(
            0,
            (int) Config::get(
                'security.hsts.max_age',
                31536000
            )
        );

        $value = 'max-age='
            . $maxAge;

        if (
            (bool) Config::get(
                'security.hsts.include_subdomains',
                true
            )
        ) {
            $value .= '; includeSubDomains';
        }

        if (
            (bool) Config::get(
                'security.hsts.preload',
                false
            )
        ) {
            $value .= '; preload';
        }

        $response->header(
            'Strict-Transport-Security',
            $value
        );
    }

    private function isHttps(): bool
    {
        $https = $_SERVER['HTTPS']
            ?? null;

        if (
            is_string($https)
            && $https !== ''
            && strtolower($https) !== 'off'
        ) {
            return true;
        }

        $scheme = $_SERVER['REQUEST_SCHEME']
            ?? null;

        return is_string($scheme)
            && strtolower($scheme) === 'https';
    }
}
