<?php

namespace Gatovel\Cli;

class CommandRegistry
{
    private array $commands = [];

    public function register(
        string $name,
        Command $command
    ): void {
        $this->commands[$name] = $command;
    }

    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    public function get(string $name): Command
    {
        return $this->commands[$name];
    }

    public function all(): array
    {
        return $this->commands;
    }
}