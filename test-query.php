<?php

require_once __DIR__ . '/bootstrap.php';

use nucleo\database\Database;

try {

    $users = Database::table('users')->get();

    print_r($users);

    $user = Database::table('users')
        ->where('email', 'tom@example.com')
        ->first();

    print_r($user);

} catch (\Throwable $exception) {

    echo "Erro: " . $exception->getMessage() . PHP_EOL;
}
