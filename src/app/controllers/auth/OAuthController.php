<?php

namespace app\controllers\auth;

use app\controllers\Controller;
use nucleo\auth\authentication\Auth;
use nucleo\auth\oauth\OAuth;
use nucleo\auth\oauth\OAuthAccountService;
use nucleo\auth\oauth\OAuthState;
use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

class OAuthController extends Controller
{
    public function start(
        Request $request,
        object $parameters
    ): Response {

        $providerName = $parameters->provider;

        $provider = OAuth::provider(
            $providerName
        );

        $state = OAuthState::generate();

        $authorizationUrl = $provider->getAuthorizationUrl(
            $state
        );

        return Response::redirect(
            $authorizationUrl
        );
    }

    public function callback(
        Request $request,
        object $parameters
    ): Response {

        $providerName = $parameters->provider;

        $code = $request->query(
            'code',
            ''
        );

        $state = $request->query(
            'state',
            ''
        );

        if (
            !is_string($code)
            || $code === ''
        ) {
            return Response::json(
                [
                    'error' => 'Authorization code não encontrado.'
                ],
                400
            );
        }

        if (
            !is_string($state)
            || $state === ''
        ) {
            return Response::json(
                [
                    'error' => 'Estado OAuth não encontrado.'
                ],
                400
            );
        }

        OAuthState::validate(
            $state
        );

        $provider = OAuth::provider(
            $providerName
        );

        $accessToken = $provider->getAccessToken(
            $code
        );

        $oauthUser = $provider->getUser(
            $accessToken
        );

        $user = OAuthAccountService::findOrCreateUser(
            $providerName,
            $oauthUser
        );

        Auth::login(
            $user
        );

        return Response::redirect(
            '/home'
        );
    }
}