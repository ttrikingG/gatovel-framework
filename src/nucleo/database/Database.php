<?php

namespace nucleo\database;

use PDO;

class Database
{
    private static ?Connection $connection = null;

    public static function connect(array $config): void
    {
        self::$connection = new Connection($config);
    }

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            throw new \Exception(
                'Banco de dados não conectado.'
            );
        }

        return self::$connection->getConnection();
    }
}