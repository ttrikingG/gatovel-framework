<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use nucleo\config\Config;
use nucleo\providers\ProviderLoader;

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();

Config::setPath(
    __DIR__ . '/config'
);

Config::load();

ProviderLoader::load(
    Config::get('app.providers', [])
);

require_once __DIR__ . '/src/app/routes/Web.php';