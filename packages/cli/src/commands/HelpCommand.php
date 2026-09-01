<?php

namespace Gatovel\Cli\commands;

use Gatovel\Cli\Command;
use Gatovel\Cli\CommandRegistry;

class HelpCommand extends Command
{
    public function __construct(
        private CommandRegistry $registry
    ) {
    }

    public function name(): string
    {
        return 'help';
    }

    public function description(): string
    {
        return 'Show available commands';
    }

    public function handle(array $arguments): int
    {
        echo "Gatovel Framework CLI" . PHP_EOL;
        echo PHP_EOL;

        echo "Usage:" . PHP_EOL;
        echo "  php gatovel <command>" . PHP_EOL;
        echo PHP_EOL;

        echo "Commands:" . PHP_EOL;

        foreach ($this->registry->all() as $command) {

            echo sprintf(
                "  %-12s %s",
                $command->name(),
                $command->description()
            ) . PHP_EOL;
        }

        return 0;
    }
}