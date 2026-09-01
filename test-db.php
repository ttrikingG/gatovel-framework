<?php

require_once __DIR__ . '/bootstrap.php';

use nucleo\database\Database;

try {

    $pdo = Database::connection();

    echo "Banco conectado com sucesso!" . PHP_EOL;

    echo "Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . PHP_EOL;

} catch (\Throwable $exception) {

    echo "Erro: " . $exception->getMessage() . PHP_EOL;
}
