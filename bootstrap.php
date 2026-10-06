<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use nucleo\config\Config;
use nucleo\container\Container;
use nucleo\providers\ProviderLoader;

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();

Config::setPath(
    __DIR__ . '/config'
);

Config::load();

$container = new Container();

$container->instance(
    Container::class,
    $container
);

ProviderLoader::load(
    Config::get('app.providers', []),
    $container
);

require_once __DIR__ . '/src/app/routes/Web.php';
