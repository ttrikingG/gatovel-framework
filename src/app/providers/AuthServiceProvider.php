<?php

namespace app\providers;

use app\auth\AppEmailVerificationService;
use app\auth\AppMfaService;
use app\auth\AppOAuthAccountRepository;
use app\auth\AppUserRepository;
use app\models\User;
use nucleo\auth\authentication\Auth;
use nucleo\auth\oauth\OAuth;
use nucleo\auth\oauth\OAuthAccountService;
use nucleo\auth\oauth\providers\GoogleProvider;
use nucleo\auth\password\PasswordRecovery;
use nucleo\auth\providers\ActiveRecordUserProvider;
use nucleo\auth\verification\EmailVerification;
use nucleo\config\Config;

class AuthServiceProvider
{
    public static function boot(): void
    {
        $userRepository = new AppUserRepository();

        $oauthAccountRepository = new AppOAuthAccountRepository();

        Auth::setProvider(
            new ActiveRecordUserProvider(
                User::class
            )
        );

        Auth::setEmailVerificationService(
            new AppEmailVerificationService()
        );

        Auth::setMfaService(
            new AppMfaService()
        );

        PasswordRecovery::setRepository(
            $userRepository
        );

        EmailVerification::setRepository(
            $userRepository
        );

        OAuthAccountService::setUserRepository(
            $userRepository
        );

        OAuthAccountService::setAccountRepository(
            $oauthAccountRepository
        );

        OAuth::register(
            'google',
            new GoogleProvider(
                Config::get('oauth.google.client_id'),
                Config::get('oauth.google.client_secret'),
                Config::get('oauth.google.redirect_uri')
            )
        );
    }
}
