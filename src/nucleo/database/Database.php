<?php

namespace nucleo\database;

use PDO;
use nucleo\database\connection\Connection;
use nucleo\database\query\QueryBuilder;
use nucleo\database\query\Grammar;
use nucleo\database\query\grammars\MySQLGrammar;
use nucleo\database\query\grammars\PostgresGrammar;
use nucleo\database\query\grammars\SQLiteGrammar;

class Database
{
    private static ?Connection $connection = null;

    private static ?Grammar $grammar = null;

    public static function connect(array $config): void
    {
        self::$connection = new Connection($config);

        $driver = $config['connection'] ?? 'mysql';

        self::$grammar = match ($driver) {
            'mysql' => new MySQLGrammar(),
            'pgsql' => new PostgresGrammar(),
            'sqlite' => new SQLiteGrammar(),

            default => throw new \Exception(
                "Grammar não suportada: {$driver}"
            ),
        };
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

    public static function table(string $table): QueryBuilder
    {
        if (self::$grammar === null) {
            throw new \Exception(
                'Banco de dados não conectado.'
            );
        }

        return new QueryBuilder(
            self::connection(),
            $table,
            self::$grammar
        );
    }
}