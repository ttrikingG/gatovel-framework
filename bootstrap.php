<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Gatovel\Database\Database;
use nucleo\auth\authentication\Auth;
use nucleo\auth\oauth\OAuth;
use nucleo\auth\oauth\OAuthAccountService;
use nucleo\auth\oauth\providers\GoogleProvider;
use nucleo\auth\providers\ActiveRecordUserProvider;
use nucleo\auth\password\PasswordRecovery;
use nucleo\auth\verification\EmailVerification;
use nucleo\mail\Mail;
use nucleo\mail\transport\LogMailTransport;
use app\auth\AppEmailVerificationService;
use app\auth\AppMfaService;
use app\auth\AppOAuthAccountRepository;
use app\auth\AppUserRepository;

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();

$databaseConfig = require __DIR__ . '/config/database.php';

Database::connect(
    $databaseConfig
);

$userRepository = new AppUserRepository();

$oauthAccountRepository = new AppOAuthAccountRepository();

Auth::setProvider(
    new ActiveRecordUserProvider(
        \app\models\User::class
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
        $_ENV['GOOGLE_OAUTH_CLIENT_ID'] ?? '',
        $_ENV['GOOGLE_OAUTH_CLIENT_SECRET'] ?? '',
        $_ENV['GOOGLE_OAUTH_REDIRECT_URI'] ?? ''
    )
);

Mail::setTransport(
    new LogMailTransport(
        __DIR__ . '/storage/logs/mail.log'
    )
);

require_once __DIR__ . '/src/app/routes/Web.php';