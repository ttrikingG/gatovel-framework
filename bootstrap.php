<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Gatovel\Database\Database;
use nucleo\auth\authentication\Auth;
use nucleo\auth\oauth\OAuth;
use nucleo\auth\oauth\providers\GoogleProvider;
use nucleo\auth\providers\ActiveRecordUserProvider;

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();

$databaseConfig = require __DIR__ . '/config/database.php';

Database::connect(
    $databaseConfig
);

Auth::setProvider(
    new ActiveRecordUserProvider(
        \app\models\User::class
    )
);

OAuth::register(
    'google',
    new GoogleProvider(
        $_ENV['GOOGLE_OAUTH_CLIENT_ID'] ?? '',
        $_ENV['GOOGLE_OAUTH_CLIENT_SECRET'] ?? '',
        $_ENV['GOOGLE_OAUTH_REDIRECT_URI'] ?? ''
    )
);

require_once __DIR__ . '/src/app/routes/Web.php';