<?php

namespace nucleo\database\query;

use PDO;

class QueryBuilder
{
    private PDO $connection;

    private string $table;

    private Grammar $grammar;

    private array $wheres = [];

    private array $bindings = [];

    private array $columns = [];

    private ?int $limit = null;

    public function __construct(
        PDO $connection,
        string $table,
        Grammar $grammar
    ) {
        $this->connection = $connection;
        $this->table = $table;
        $this->grammar = $grammar;
    }

    public function where(
        string $column,
        mixed $value,
        string $operator = '='
    ): static {
        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;

        return $this;
    }

    public function get(): array
    {
        $sql = $this->grammar->compileSelect(
            $this->table,
            $this->columns,
            $this->wheres,
            $this->limit
        );

        $statement = $this->connection->prepare($sql);

        $statement->execute($this->bindings);

        return $statement->fetchAll();
    }

    public function first(): ?array
    {
        $sql = $this->grammar->compileSelect(
            $this->table,
            $this->columns,
            $this->wheres,
            1
        );

        $statement = $this->connection->prepare($sql);

        $statement->execute($this->bindings);

        $result = $statement->fetch();

        return $result === false ? null : $result;
    }
}