<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use nucleo\database\Database;

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();

$databaseConfig = require __DIR__ . '/config/database.php';

Database::connect(
    $databaseConfig
);

require_once __DIR__ . '/src/app/routes/Web.php';

